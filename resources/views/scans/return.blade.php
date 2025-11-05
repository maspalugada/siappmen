<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Scan Pengembalian Instrumen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md mx-auto p-4">
        <header class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Scan Pengembalian</h1>
            <p class="text-sm text-gray-500 mt-1">Arahkan kamera ke QR code pada instrumen.</p>
        </header>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-black">
                <video id="preview" class="w-full h-64"></video>
            </div>

            <form id="returnForm" action="{{ route('scan.return.post') }}" class="p-6 space-y-4">
                <input type="hidden" id="qr_content" name="qr_content">

                <div>
                    <label for="pouch_code" class="block text-sm font-medium text-gray-700">Kode Pouch</label>
                    <input type="text" id="pouch_code" name="pouch_code" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>

                <div>
                    <label for="patient_name" class="block text-sm font-medium text-gray-700">Nama Pasien</label>
                    <input type="text" id="patient_name" name="patient_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>

                <div>
                    <label for="qty" class="block text-sm font-medium text-gray-700">Jumlah (Qty)</label>
                    <input type="number" id="qty" name="qty" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="1" min="1" required>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white py-2.5 px-4 rounded-md hover:bg-indigo-700 font-semibold">
                    Simpan Pengembalian
                </button>
            </form>
        </div>

        <div id="result" class="mt-4 hidden p-3 rounded-md text-sm text-center"></div>
    </div>
</body>
</html>
