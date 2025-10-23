<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>QR Code Pouch</title>
  <style>
    body { font-family: sans-serif; }
    .page-break { page-break-after: always; }
    .box {
        width: 45%; float: left; margin: 10px; text-align: center; border: 1px solid #ccc; padding: 10px;
    }
  </style>
</head>
<body>
  <h2 style="text-align:center;">Daftar QR Code Pouch</h2>
  @foreach($pouches as $index => $pouch)
    <div class="box">
      <p><strong>{{ $pouch->pouch_code }}</strong></p>
      {!! QrCode::size(150)->generate($pouch->pouch_code) !!}
      <p>{{ $pouch->instrument->name ?? '-' }}</p>
    </div>

    @if(($index + 1) % 6 == 0)
      <div class="page-break"></div>
    @endif
  @endforeach
</body>
</html>
