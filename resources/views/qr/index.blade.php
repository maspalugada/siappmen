<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar QR Pouch</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 p-6 font-sans">
  <div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-semibold mb-4">📦 Daftar QR Code Pouch</h2>

    <a href="{{ route('qr.pdf') }}" class="bg-green-600 text-white px-4 py-2 rounded inline-block mb-4">📄 Cetak ke PDF</a>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
      @foreach($pouches as $pouch)
        <div class="text-center border p-4 rounded shadow-sm">
          <h3 class="font-semibold mb-2">{{ $pouch->pouch_code }}</h3>
          {!! QrCode::size(150)->generate($pouch->pouch_code) !!}
          <p class="text-sm mt-2 text-gray-600">{{ $pouch->instrument->name ?? '-' }}</p>
        </div>
      @endforeach
    </div>
  </div>
</body>
</html>
