{{-- resources/views/pdf/undangan.blade.php --}}
{{-- DomPDF-safe: NO flexbox, NO grid, NO Google Fonts, pakai table layout --}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    @page { size: A4 portrait; margin: 0; }

    body {
        width: 210mm;
        font-family: Georgia, "Times New Roman", serif;
        font-size: 10.5pt;
        color: #1A1A1A;
    }

    .header { background-color: #003366; padding: 0; width: 210mm; }
    .header-inner { padding: 7mm 8mm 6mm; }
    .header-table { width: 100%; }
    .header-table td { vertical-align: middle; }

    .logo-cell { width: 22mm; text-align: center; }
    .logo-cell img { width: 18mm; height: 18mm; }

    .text-cell { text-align: center; padding: 0 3mm; }

    .h-univ { font-size: 13pt; font-weight: bold; color: #FFFFFF; letter-spacing: 0.5px; }
    .h-faculty { font-size: 10pt; font-weight: bold; color: #FFD700; margin-top: 1.5mm; }
    .h-prodi { font-size: 8pt; color: #B0C8E8; margin-top: 1mm; }
    .h-sep { border: none; border-top: 0.5px solid rgba(255,215,0,0.4); margin: 2mm auto; width: 60%; }
    .h-contact { font-size: 7.5pt; color: #9BB8D8; }

    .gold-bar { background-color: #FFD700; height: 3px; width: 100%; }
    .navy-bar { background-color: #002244; height: 1px; width: 100%; }

    .body { padding: 6mm 14mm 5mm; }

    .meta-table { width: 100%; margin-bottom: 4mm; }
    .meta-table td { font-size: 10pt; line-height: 1.7; vertical-align: top; }
    .meta-lbl { width: 28mm; }
    .meta-sep { width: 8mm; }
    .meta-val-bold { font-weight: bold; color: #003366; }

    .divider-judul { border: none; border-top: 1.5px solid #003366; margin: 3mm 0 1mm; }
    .divider-judul-thin { border: none; border-top: 0.5px solid #C9A84C; margin: 0 0 3mm; }

    .judul-undangan {
        font-size: 15pt;
        font-weight: bold;
        color: #003366;
        letter-spacing: 3px;
        text-transform: uppercase;
        text-align: center;
        margin-bottom: 1.5mm;
    }

    .subjudul {
        font-size: 10.5pt;
        font-style: italic;
        color: #444;
        text-align: center;
        margin-bottom: 4mm;
    }

    .recipient { font-size: 10pt; margin-bottom: 3.5mm; line-height: 1.65; }
    .indent-1  { padding-left: 5mm; }
    .indent-2  { padding-left: 10mm; }

    .salam { font-size: 10pt; margin-bottom: 2mm; }

    .para {
        font-size: 10.5pt;
        line-height: 1.8;
        text-align: justify;
        text-indent: 10mm;
        margin-bottom: 2.5mm;
    }

    .detail-box {
        border: 1px solid #BAC9E8;
        border-left: 4px solid #003366;
        background-color: #EEF3FF;
        padding: 3.5mm 5mm;
        margin: 3mm 0;
    }

    .detail-title {
        font-size: 8.5pt;
        font-weight: bold;
        color: #003366;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 2mm;
    }

    .detail-table { width: 100%; }
    .detail-table td { font-size: 9.5pt; line-height: 1.75; vertical-align: top; }
    .d-lbl { width: 30mm; font-weight: bold; color: #003366; }
    .d-sep { width: 8mm; }
    .d-val em { font-style: italic; color: #004080; }

    .salam-close { font-size: 10pt; margin: 2mm 0 3mm; }

    .ttd-table { width: 100%; }
    .ttd-city  { font-size: 10pt; margin-bottom: 1mm; }
    .ttd-role  { font-size: 10pt; margin-bottom: 12mm; }
    .ttd-line  { border: none; border-top: 1px solid #444; width: 55mm; margin: 0 auto 2mm; }
    .ttd-nama { font-size: 10pt; font-weight: bold; color: #111; }
    .ttd-nip  { font-size: 8.5pt; color: #555; margin-top: 1mm; }

    .footer { padding: 0 14mm 3mm; margin-top: 4mm; }
    .footer-gold { background-color: #FFD700; height: 2px; margin-bottom: 1px; }
    .footer-thin { background-color: #CCCCCC; height: 0.5px; margin-bottom: 2mm; }
    .footer-text { text-align: center; font-size: 7.5pt; color: #999; }
</style>
</head>
<body>

@foreach($users as $user)

<div class="header">
    <div class="header-inner">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('assets/images/logo-unair.png') }}">
                </td>
                <td class="text-cell">
                    <div class="h-univ">UNIVERSITAS AIRLANGGA</div>
                    <div class="h-faculty">UNIT PERPUSTAKAAN PUSAT</div>
                    <div class="h-prodi">Layanan Literasi & Pengelolaan Koleksi Digital</div>
                    <hr class="h-sep">
                    <div class="h-contact">
                        Kampus B Jl. Dharmawangsa Dalam, Surabaya 60286 | Telp. (031) 5914042 | lib@unair.ac.id
                    </div>
                </td>
                <td class="logo-cell">
                    <img src="{{ public_path('assets/images/logo-unair.png') }}">
                </td>
            </tr>
        </table>
    </div>
    <div class="gold-bar"></div>
    <div class="navy-bar"></div>
</div>

<div class="body">

    <table class="meta-table">
        <tr>
            <td class="meta-lbl">Nomor</td>
            <td class="meta-sep">:</td>
            <td><span class="meta-val-bold">{{ $nomor_surat ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="meta-lbl">Lampiran</td>
            <td class="meta-sep">:</td>
            <td>{{ $lampiran ?? '-' }}</td>
        </tr>
        <tr>
            <td class="meta-lbl">Perihal</td>
            <td class="meta-sep">:</td>
            <td><span class="meta-val-bold">{{ $perihal ?? '-' }}</span></td>
        </tr>
    </table>

    <hr class="divider-judul">
    <hr class="divider-judul-thin">
    <div class="judul-undangan">Undangan</div>
    <div class="subjudul">{{ $judul_acara ?? '-' }}</div>

    <div class="recipient">
        <div>Kepada Yth.</div>
        <div class="indent-1">Bapak/Ibu {{ $user->name }}</div>
        <div class="indent-1">{{ $kepada_instansi ?? '-' }}</div>
        <div class="indent-1">di –</div>
        <div class="indent-2">Tempat</div>
    </div>

    <div class="salam">Assalamu’alaikum Wr. Wb.</div>

    <div class="para">
        Dengan hormat, dalam rangka meningkatkan kompetensi literasi digital dan penguasaan alat bantu pengelolaan referensi ilmiah, Unit Perpustakaan Pusat Universitas Airlangga menyelenggarakan kegiatan <strong>{{ $judul_acara ?? '-' }}</strong>.
    </div>

    <div class="para">
        Sehubungan dengan hal tersebut, kami mengundang Bapak/Ibu untuk hadir dan berpartisipasi aktif dalam kegiatan dimaksud.
    </div>

    <div class="detail-box">
        <div class="detail-title">Informasi Kegiatan</div>
        <table class="detail-table">
            <tr>
                <td class="d-lbl">Hari, Tanggal</td>
                <td class="d-sep">:</td>
                <td class="d-val">{{ $hari_tanggal ?? '-' }}</td>
            </tr>
            <tr>
                <td class="d-lbl">Waktu</td>
                <td class="d-sep">:</td>
                <td class="d-val">{{ $waktu ?? '-' }}</td>
            </tr>
            <tr>
                <td class="d-lbl">Tempat</td>
                <td class="d-sep">:</td>
                <td class="d-val">{{ $tempat ?? '-' }}</td>
            </tr>
            <tr>
                <td class="d-lbl">Materi</td>
                <td class="d-sep">:</td>
                <td class="d-val"><em>{{ $materi ?? '-' }}</em></td>
            </tr>
            <tr>
                <td class="d-lbl">Narasumber</td>
                <td class="d-sep">:</td>
                <td class="d-val">{{ $narasumber ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="salam-close">Wassalamu’alaikum Wr. Wb.</div>

    <table class="ttd-table">
        <tr>
            <td></td>
            <td style="width:65mm; text-align:center;">
                <div class="ttd-city">{{ $kota ?? '-' }}, {{ $tanggal ?? '-' }}</div>
                <div class="ttd-role">{{ $ttd_role ?? '-' }}</div>
                <hr class="ttd-line">
                <div class="ttd-nama">{{ $ttd_nama ?? '-' }}</div>
                <div class="ttd-nip">NIP. {{ $ttd_nip ?? '-' }}</div>
            </td>
        </tr>
    </table>

</div>

<div class="footer">
    <div class="footer-gold"></div>
    <div class="footer-thin"></div>
    <div class="footer-text">
        Universitas Airlangga • Unit Perpustakaan Pusat • lib@unair.ac.id • (031) 5914042
    </div>
</div>

@if(!$loop->last)
    <div style="page-break-after: always;"></div>
@endif

@endforeach

</body>
</html>