<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: DejaVu Sans, Arial, sans-serif;
        font-size: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    td {
        width: 20%;
        height: 80px;
        border: 0.5px dashed #ccc;
        text-align: center;
        vertical-align: middle;
        padding: 3px;
        overflow: hidden;
    }

    td.empty {
        border: 0.5px dashed #eee;
        background: #fff;
    }

    .barcode-img {
        width: 90%;
        max-height: 28px;
        display: block;
        margin: 0 auto 2px;
    }

    .kode  { font-size: 7pt; color: #888; margin-bottom: 1px; }
    .nama  { font-size: 8pt; font-weight: bold; margin-bottom: 1px; }
    .harga { font-size: 9pt; font-weight: bold; color: #198754; }

    .page-break { page-break-after: always; }
</style>
</head>
<body>

@php
    $cols       = 5;
    $rows       = 8;
    $totalSlot  = $cols * $rows;
    $items      = collect($barangs)->values();
    $itemCount  = $items->count();
    $itemIdx    = 0;
    $pageNum    = 0;
@endphp

@while($itemIdx < $itemCount)
@php
    $pageNum++;
    $slotStart = ($pageNum === 1) ? $startSlot : 0;
    $slot      = 0;
@endphp

<div class="{{ $pageNum > 1 ? 'page-break' : '' }}">
<table>
@for($row = 0; $row < $rows; $row++)
<tr>
@for($col = 0; $col < $cols; $col++)
@php $slot = $row * $cols + $col; @endphp
@if($slot < $slotStart || $itemIdx >= $itemCount)
    <td class="empty"></td>
@else
@php $item = $items[$itemIdx]; $itemIdx++; @endphp
    <td>
        <img class="barcode-img"
             src="data:image/png;base64,{{ $barcodes[$item->id_barang] }}"
             alt="barcode">
        <div class="kode">{{ $item->id_barang }}</div>
        <div class="nama">{{ mb_strlen($item->nama) > 18 ? mb_substr($item->nama,0,18).'…' : $item->nama }}</div>
        <div class="harga">Rp {{ number_format($item->harga,0,',','.') }}</div>
    </td>
@endif
@endfor
</tr>
@endfor
</table>
</div>

@endwhile

</body>
</html>