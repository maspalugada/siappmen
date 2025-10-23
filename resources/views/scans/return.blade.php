<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scan Pengembalian Instrumen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="container mx-auto py-6 px-4">
        <h2 class="text-2xl font-semibold mb-4">📷 Scan Pengembalian Instrumen Kotor</h2>

        <video id="preview" width="100%" height="240" class="border rounded-lg shadow"></video>

        <form id="returnForm" class="mt-4 bg-white p-4 rounded-lg shadow">
            @csrf
            <input type="hidden" id="pouch_code" name="pouch_code">

            <label class="block mb-2 font-medium">Nama Pasien</label>
            <input type="text" id="patient_name" name="patient_name" class="border rounded w-full mb-3 p-2" required>

            <label class="block mb-2 font-medium">Jumlah (Qty)</label>
            <input type="number" id="qty" name="qty" class="border rounded w-full mb-3 p-2" value="1" min="1" required>

            <button type="submit" class="bg-blue-600 text-white w-full py-2 rounded">📥 Simpan Pengembalian</button>
        </form>

        <div id="result" class="mt-4 hidden p-3 rounded"></div>
    </div>

<script type="module">
import QrScanner from "https://unpkg.com/qr-scanner@1.4.2/qr-scanner.min.js";

const videoElem = document.getElementById('preview');
const scanner = new QrScanner(videoElem, result => {
    document.getElementById('pouch_code').value = result;
    const div = document.getElementById('result');
    div.textContent = "QR terdeteksi: " + result;
    div.className = "bg-blue-100 text-blue-800 p-3 rounded";
    div.classList.remove("hidden");
}, {highlightScanRegion:true});

QrScanner.hasCamera().then(has => { if(has) scanner.start(); else alert("Kamera tidak tersedia!"); });

document.getElementById('returnForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = {
        pouch_code: document.getElementById('pouch_code').value,
        patient_name: document.getElementById('patient_name').value,
        qty: document.getElementById('qty').value
    };
    const res = await fetch("{{ route('scan.return.post') }}", {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify(data)
    });
    const json = await res.json();
    const div = document.getElementById('result');
    if (json.error) {
        div.textContent = json.error;
        div.className = "bg-red-100 text-red-700 p-3 rounded";
    } else {
        div.textContent = json.message;
        div.className = "bg-green-100 text-green-700 p-3 rounded";
    }
});
</script>
</body>
</html>
