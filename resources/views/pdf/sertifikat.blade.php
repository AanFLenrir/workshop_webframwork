{{-- resources/views/pdf/sertifikat.blade.php --}}
{{-- DomPDF-safe: NO flexbox, NO grid, NO Google Fonts, pakai table layout --}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    @page { size: A4 landscape; margin: 0; }

    body {
        width: 297mm;
        height: 210mm;
        font-family: Georgia, "Times New Roman", serif;
        background: #FFFDF5;
    }

    .page {
        width: 297mm;
        height: 210mm;
        position: relative;
        background: #FFFDF5;
    }

    .border-outer {
        position: absolute;
        top: 8mm; left: 8mm;
        right: 8mm; bottom: 8mm;
        border: 3px solid #7A5310;
    }
    .border-inner {
        position: absolute;
        top: 11.5mm; left: 11.5mm;
        right: 11.5mm; bottom: 11.5mm;
        border: 1px solid #C9A84C;
    }

    .content {
        position: absolute;
        top: 14mm; left: 20mm;
        right: 20mm; bottom: 14mm;
        text-align: center;
    }

    .header-table { width: 100%; margin-bottom: 4mm; }
    .header-table td { vertical-align: middle; }
    .logo-td { width: 22mm; text-align: center; }
    .logo-td img { width: 18mm; height: 18mm; }
    .univ-td { text-align: center; }

    .univ-name {
        font-size: 11pt;
        font-weight: bold;
        color: #4A2C08;
        letter-spacing: 1px;
    }
    .faculty-name {
        font-size: 9pt;
        color: #7A5310;
        letter-spacing: 0.5px;
        margin-top: 1mm;
    }

    .divider-gold {
        border: none;
        border-top: 1.5px solid #C9A84C;
        margin: 3mm auto;
        width: 90%;
    }
    .divider-dark {
        border: none;
        border-top: 0.5px solid #7A5310;
        margin: 0 auto 3mm;
        width: 90%;
    }

    .title-sertifikat {
        font-size: 38pt;
        font-weight: bold;
        color: #4A2C08;
        letter-spacing: 6px;
        text-transform: uppercase;
        line-height: 1;
        margin-bottom: 1mm;
    }
    .title-sub {
        font-size: 10pt;
        color: #8B6014;
        letter-spacing: 7px;
        text-transform: uppercase;
        margin-bottom: 3mm;
    }

    .star-row { font-size: 9pt; color: #C9A84C; margin-bottom: 3mm; }

    .label-diberikan {
        font-size: 10pt;
        font-style: italic;
        color: #5C400A;
        margin-bottom: 2mm;
    }

    .nama-penerima {
        font-size: 28pt;
        font-style: italic;
        font-weight: bold;
        color: #2D1800;
        margin-bottom: 1mm;
    }

    .nama-line {
        border: none;
        border-top: 1.5px solid #C9A84C;
        width: 70mm;
        margin: 0 auto 3mm;
    }

    .desc {
        font-size: 9.5pt;
        color: #3D2408;
        line-height: 1.65;
        margin-bottom: 2mm;
    }
    .desc em { font-style: italic; color: #6B4810; }

    .tanggal { font-size: 9pt; color: #6B4810; margin-bottom: 4mm; }

    .ttd-table { width: 100%; }
    .ttd-table td { text-align: center; width: 50%; vertical-align: top; }

    .ttd-role { font-size: 8.5pt; font-weight: bold; color: #4A2C08; margin-bottom: 9mm; }

    .ttd-line {
        border: none;
        border-top: 1px solid #7A5310;
        width: 55mm;
        margin: 0 auto 2mm;
    }

    .ttd-nama { font-size: 8.5pt; font-weight: bold; color: #2D1800; }
    .ttd-nip  { font-size: 7.5pt; color: #7A5520; margin-top: 1mm; }

    .cert-no {
        position: absolute;
        bottom: 2mm; left: 0; right: 0;
        text-align: center;
        font-size: 7pt;
        color: #9B7A30;
        letter-spacing: 2px;
    }
</style>
</head>
<body>

@foreach($users as $user)

<div class="page">
    <div class="border-outer"></div>
    <div class="border-inner"></div>

    <div class="content">

        <table class="header-table">
            <tr>
                <td class="logo-td">
                    <img src="{{ public_path('assets/images/logo-unair.png') }}">
                </td>
                <td class="univ-td">
                    <div class="univ-name">UNIVERSITAS AIRLANGGA</div>
                    <div class="faculty-name">UNIT PERPUSTAKAAN PUSAT • SURABAYA</div>
                </td>
                <td class="logo-td">
                    <img src="{{ public_path('assets/images/logo-unair.png') }}">
                </td>
            </tr>
        </table>

        <hr class="divider-gold">
        <hr class="divider-dark">

        <div class="title-sertifikat">Sertifikat</div>
        <div class="title-sub">Penghargaan</div>

        <div class="star-row">◆ ◆ ◆</div>

        <div class="label-diberikan">Diberikan dengan bangga kepada</div>

        <div class="nama-penerima">{{ $user->name }}</div>
        <hr class="nama-line">

        <div class="desc">
            Atas keberhasilan menyelesaikan pelatihan
            <em>&ldquo;{{ $program ?? '-' }}&rdquo;</em><br>
            {{ $penyelenggara ?? '-' }}
        </div>

        <div class="tanggal">
            {{ $kota ?? '-' }}, {{ $tanggal ?? '-' }}
        </div>

        <table class="ttd-table">
            <tr>
                <td>
                    <div class="ttd-role">Kepala Perpustakaan</div>
                    <hr class="ttd-line">
                    <div class="ttd-nama">{{ $ttd_kiri_nama ?? '-' }}</div>
                    <div class="ttd-nip">NIP. {{ $ttd_kiri_nip ?? '-' }}</div>
                </td>
                <td>
                    <div class="ttd-role">Koordinator Program</div>
                    <hr class="ttd-line">
                    <div class="ttd-nama">{{ $ttd_kanan_nama ?? '-' }}</div>
                    <div class="ttd-nip">NIP. {{ $ttd_kanan_nip ?? '-' }}</div>
                </td>
            </tr>
        </table>

    </div>

    <div class="cert-no">
        No: PERPUS/SERT/{{ date('Y') }}/{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}
    </div>

</div>

@if(!$loop->last)
    <div style="page-break-after: always;"></div>
@endif

@endforeach

</body>
</html>