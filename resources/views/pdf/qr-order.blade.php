<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>QR Order - {{ $order->order_no }}</title>
    <style>
        @page { margin: 10px; }
        body {
            font-family: sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .qr-container {
            border: 2px solid #4CAF50;
            padding: 10px;
            margin: 10px auto;
            width: 250px;
            border-radius: 10px;
        }
        .title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 6px;
            color: #4CAF50;
        }
        .meta {
            font-size: 12px;
            text-align: left;
            margin-top: 8px;
            line-height: 1.4;
        }
        img {
            width: 140px;
            height: 140px;
        }
        small {
            color: gray;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="qr-container">
        <div class="title">QR Permintaan Instrumen CSSD</div>

        {{-- QR Code --}}
        <img src="data:image/svg+xml;base64,{{ $qrSvg }}" alt="QR Code">

        {{-- Informasi Order --}}
        <div class="meta">
            <p><strong>No Order:</strong> {{ $order->order_no }}</p>
            <p><strong>Ruangan:</strong> {{ $order->unit->name ?? '-' }}</p>
            <p><strong>Tanggal:</strong> {{ $order->date_request }}</p>
        </div>

        <small>Tempelkan QR ini pada pouch instrumen</small>
    </div>
</body>
</html>
