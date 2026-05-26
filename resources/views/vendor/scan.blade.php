@extends('layouts.app')
@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="mdi mdi-qrcode-scan"></i> Vendor Scan QR Pesanan</h5>
            </div>
            <div class="card-body p-2">
                <div id="reader" style="width:100%;"></div>
            </div>
            <div class="card-body" id="result" style="display:none;"></div>
            <div class="card-footer">
                <a href="{{ url()->previous() }}" class="btn btn-secondary w-100">← Kembali</a>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    const html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: 200 },
        (decodedText) => {
            fetch(`/vendor/scan-qr/cek/${decodedText}`)
                .then(res => res.json())
                .then(data => {
                    const resultDiv = document.getElementById('result');
                    resultDiv.style.display = 'block';
                    if (data.status === 'found') {
                        let menu = data.menu.map(m => `<li>${m.nama_menu} x${m.qty} - Rp${m.harga}</li>`).join('');
                        resultDiv.innerHTML = `
                            <h5>Order: ${data.order_code}</h5>
                            <ul>${menu}</ul>
                            <p>Status: ${data.status_bayar}</p>`;
                    } else {
                        resultDiv.innerHTML = '<p class="text-danger">Pesanan tidak ditemukan</p>';
                    }
                });
        }
    );
</script>
@endsection