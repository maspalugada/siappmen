<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Label QR - {{ $order->order_no }}</title>
    <style>
        @page {
            margin: 20px;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .page {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .label {
            border: 1px solid #4CAF50;
            border-radius: 8px;
            width: 48%;
            height: 220px;
            margin-bottom: 10px;
            text-align: center;
            padding: 5px;
            box-sizing: border-box;
        }
        .label h3 {
            font-size: 12px;
            margin: 3px 0;
            color: #2e7d32;
        }
        .label img {
            width: 120px;
            height: 120px;
        }
        .meta {
            text-align: left;
            font-size: 11px;
            margin: 5px 0 0 8px;
        }
        .meta p {
            margin: 2px 0;
        }
        .footer {
            text-align: center;
            font-size: 9px;
            color: gray;
        }
    </style>
</head>
<body>
    <div class="page">
        @for($i = 0; $i < 4; $i++)
        <div class="label">
            <h3>QR CSSD - {{ $order->unit->name ?? '-' }}</h3>
            <img src="data:image/svg+xml;base64,{{ $qrSvg }}" alt="QR">
            <div class="meta">
                <p><strong>No Order:</strong> {{ $order->order_no }}</p>
                <p><strong>Tanggal:</strong> {{ $order->date_request }}</p>
            </div>
            <div class="footer">Tempelkan label ini pada pouch instrumen</div>
        </div>
        @endfor
    </div>
</body>
</html>
