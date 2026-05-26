<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Barcode Toko - {{ $toko->nama_toko }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f5f5f5;
        }
        .label {
            background: white;
            border: 2px solid #333;
            border-radius: 8px;
            padding: 20px 30px;
            text-align: center;
            width: 350px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .label h3 {
            font-size: 18px;
            margin-bottom: 6px;
            color: #222;
        }
        .label p {
            font-size: 12px;
            color: #666;
            margin-bottom: 16px;
        }
        .barcode-img {
            width: 100%;
            height: 70px;
            object-fit: contain;
        }
        .barcode-code {
            font-size: 11px;
            color: #444;
            margin-top: 8px;
            letter-spacing: 1px;
        }
        .btn-print {
            display: block;
            margin: 20px auto 0;
            padding: 8px 24px;
            background: #4472C4;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        @media print {
            body { background: white; }
            .btn-print { display: none; }
        }
    </style>
</head>
<body>

<div>
    <div class="label">
        <h3>{{ $toko->nama_toko }}</h3>
        <p>{{ $toko->alamat ?? '' }}</p>
        <img class="barcode-img"
             src="data:image/png;base64,{{ $barcode }}"
             alt="Barcode {{ $toko->barcode }}">
        <div class="barcode-code">{{ $toko->barcode }}</div>
    </div>

    <button class="btn-print" onclick="window.print()">🖨️ Cetak Label</button>
</div>

</body>
</html>