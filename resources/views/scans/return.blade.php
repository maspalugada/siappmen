<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scan Pengembalian Instrumen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="container mx-auto py-6 px-4">
        <h2 class="text-2xl font-semibold mb-4">📷 Scan Pengembalian Instrumen Kotor</h2>

        <video id="preview" width="100%" height="240" class="border rounded-lg shadow"></video>

        <form id="returnForm" action="{{ route('scan.return.post') }}" class="mt-4 bg-white p-4 rounded-lg shadow">
            <input type="hidden" id="qr_content" name="qr_content">

            <label class="block mb-2 font-medium">Kode Pouch (diinput manual)</label>
            <input type="text" id="pouch_code" name="pouch_code" class="border rounded w-full mb-3 p-2" required>

            <label class="block mb-2 font-medium">Nama Pasien</label>
            <input type="text" id="patient_name" name="patient_name" class="border rounded w-full mb-3 p-2" required>

            <label class="block mb-2 font-medium">Jumlah (Qty)</label>
            <input type="number" id="qty" name="qty" class="border rounded w-full mb-3 p-2" value="1" min="1" required>

            <button type="submit" class="bg-blue-600 text-white w-full py-2 rounded">📥 Simpan Pengembalian</button>
        </form>

        <div id="result" class="mt-4 hidden p-3 rounded"></div>
    </div>

</body>
</html>
