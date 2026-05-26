@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Barcode Reader</h2>

    <div id="scanner-container">
        <video id="video" width="300" height="200" style="border: 1px solid gray"></video>
    </div>

    <div id="hasil" class="mt-3"></div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/@zxing/library@latest"></script>
<script>
    const codeReader = new ZXing.BrowserQRCodeReader();

    codeReader.decodeFromVideoDevice(null, 'video', (result, err) => {
        if (result) {
            const kode = result.getText();

            fetch(`/barcode-reader/cari/${kode}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'found') {
                        document.getElementById('hasil').innerHTML = `
                            <div class="alert alert-success">
                                <strong>Barang Ditemukan!</strong><br>
                                ID: ${data.id_barang}<br>
                                Nama: ${data.nama_barang}<br>
                                Harga: Rp ${data.harga}
                            </div>
                        `;
                    } else {
                        document.getElementById('hasil').innerHTML = `
                            <div class="alert alert-danger">Barang tidak ditemukan.</div>
                        `;
                    }
                });
        }
    });
</script>
@endpush