@extends('layouts.app')

@section('title', 'Kunjungan Toko')

@section('content')
<div class="container-fluid py-4">
    <h4 class="fw-bold mb-4">📍 Kunjungan Toko</h4>

    <div class="row">
        {{-- PANEL SCAN + KUNJUNGAN --}}
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header fw-bold">
                    Step 1: Scan Barcode Toko
                </div>
                <div class="card-body text-center">
                    <p class="text-muted small">Arahkan kamera ke barcode pada label toko</p>
                    <div id="reader" style="width:100%;"></div>
                    <audio id="beep-sound" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>
                </div>
            </div>

            {{-- INFO TOKO --}}
            <div class="card shadow-sm mb-4" id="card-toko" style="display:none;">
                <div class="card-header fw-bold bg-success text-white">
                    ✅ Informasi Toko
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr><td width="40%"><strong>Nama Toko</strong></td><td id="info-nama">-</td></tr>
                        <tr><td><strong>Alamat</strong></td><td id="info-alamat">-</td></tr>
                        <tr><td><strong>Lat/Lng Toko</strong></td><td id="info-latlng">-</td></tr>
                        <tr><td><strong>Accuracy Toko</strong></td><td id="info-acc-toko">-</td></tr>
                    </table>
                </div>
            </div>

            {{-- AMBIL LOKASI SALES --}}
            <div class="card shadow-sm mb-4" id="card-lokasi" style="display:none;">
                <div class="card-header fw-bold">
                    Step 2: Ambil Lokasi Sales
                </div>
                <div class="card-body text-center">
                    <button class="btn btn-primary" onclick="ambilLokasiSales()">
                        📡 Ambil Lokasi Saya Sekarang
                    </button>
                    <div id="status-sales" class="mt-3 text-muted small"></div>

                    <div id="info-sales" class="mt-3" style="display:none;">
                        <table class="table table-sm text-start">
                            <tr><td width="50%"><strong>Lat/Lng Sales</strong></td><td id="sales-latlng">-</td></tr>
                            <tr><td><strong>Accuracy Sales</strong></td><td id="sales-acc">-</td></tr>
                        </table>
                        <button class="btn btn-success w-100 mt-2" onclick="kirimKunjungan()">
                            📤 Kirim Laporan Kunjungan
                        </button>
                    </div>
                </div>
            </div>

            {{-- HASIL KUNJUNGAN --}}
            <div class="card shadow-sm mb-4" id="card-hasil" style="display:none;">
                <div class="card-body text-center">
                    <h4 id="hasil-icon">-</h4>
                    <h5 id="hasil-teks">-</h5>
                    <p class="text-muted small" id="hasil-detail">-</p>
                    <button class="btn btn-primary mt-2" onclick="scanLagi()">🔄 Scan Toko Lain</button>
                </div>
            </div>
        </div>

        {{-- RIWAYAT KUNJUNGAN --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">
                    📋 Riwayat Kunjungan Saya
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Toko</th>
                                <th>Jarak</th>
                                <th>Status</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $r)
                            <tr>
                                <td>{{ $r->toko->nama_toko }}</td>
                                <td>{{ number_format($r->jarak_meter, 1) }} m</td>
                                <td>
                                    @if($r->status === 'diterima')
                                        <span class="badge bg-success">✅ Diterima</span>
                                    @else
                                        <span class="badge bg-danger">❌ Ditolak</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $r->created_at->format('d/m H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Belum ada riwayat kunjungan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Hidden input untuk data toko hasil scan --}}
<input type="hidden" id="toko-id" value="">
<input type="hidden" id="toko-lat" value="">
<input type="hidden" id="toko-lng" value="">
<input type="hidden" id="toko-acc" value="">
<input type="hidden" id="sales-latitude" value="">
<input type="hidden" id="sales-longitude" value="">
<input type="hidden" id="sales-accuracy" value="">

@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let scanner   = null;
let sudahScan = false;

// ── Auto start scanner ──
window.onload = function () { mulaiScan(); };

function mulaiScan() {
    sudahScan = false;
    document.getElementById('card-toko').style.display   = 'none';
    document.getElementById('card-lokasi').style.display = 'none';
    document.getElementById('card-hasil').style.display  = 'none';
    document.getElementById('reader').innerHTML          = '';

    scanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: { width: 300, height: 150 } });
    scanner.render(onScanSuccess, () => {});
}

function onScanSuccess(decodedText) {
    if (sudahScan) return;
    sudahScan = true;

    document.getElementById('beep-sound').play();
    scanner.clear();

    fetch(`{{ url('/toko/cari') }}/${encodeURIComponent(decodedText)}`)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'found') {
                // Simpan data toko ke hidden input
                document.getElementById('toko-id').value  = data.id;
                document.getElementById('toko-lat').value = data.latitude;
                document.getElementById('toko-lng').value = data.longitude;
                document.getElementById('toko-acc').value = data.accuracy;

                // Tampilkan info toko
                document.getElementById('info-nama').innerText   = data.nama_toko;
                document.getElementById('info-alamat').innerText = data.alamat;
                document.getElementById('info-latlng').innerText = `${data.latitude}, ${data.longitude}`;
                document.getElementById('info-acc-toko').innerText = `${data.accuracy} meter`;

                document.getElementById('card-toko').style.display   = 'block';
                document.getElementById('card-lokasi').style.display = 'block';
            } else {
                alert('Toko tidak ditemukan! Pastikan barcode terdaftar.');
                scanLagi();
            }
        })
        .catch(() => {
            alert('Gagal menghubungi server!');
            scanLagi();
        });
}

// ── Ambil lokasi sales (pakai getAccuratePosition) ──
function ambilLokasiSales() {
    const statusEl = document.getElementById('status-sales');
    statusEl.innerHTML = '⏳ Mengambil lokasi, harap tunggu...';
    statusEl.className = 'mt-3 text-muted small';

    getAccuratePosition(50, 20000)
        .then(pos => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            const acc = Math.round(pos.coords.accuracy);

            document.getElementById('sales-latitude').value  = lat;
            document.getElementById('sales-longitude').value = lng;
            document.getElementById('sales-accuracy').value  = acc;

            document.getElementById('sales-latlng').innerText = `${lat}, ${lng}`;
            document.getElementById('sales-acc').innerText    = `${acc} meter`;

            statusEl.innerHTML = `✅ Lokasi berhasil! Accuracy: <strong>${acc}m</strong>`;
            statusEl.className = 'mt-3 text-success small';
            document.getElementById('info-sales').style.display = 'block';
        })
        .catch(err => {
            statusEl.innerHTML = '❌ Gagal: ' + err.message;
            statusEl.className = 'mt-3 text-danger small';
        });
}

// ── Kirim laporan kunjungan ke server ──
function kirimKunjungan() {
    const payload = {
        toko_id:         document.getElementById('toko-id').value,
        latitude_sales:  document.getElementById('sales-latitude').value,
        longitude_sales: document.getElementById('sales-longitude').value,
        accuracy_sales:  document.getElementById('sales-accuracy').value,
        _token:          '{{ csrf_token() }}'
    };

    fetch('{{ route("toko.simpanKunjungan") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('card-toko').style.display   = 'none';
        document.getElementById('card-lokasi').style.display = 'none';
        document.getElementById('card-hasil').style.display  = 'block';

        if (data.status === 'diterima') {
            document.getElementById('hasil-icon').innerText  = '✅';
            document.getElementById('hasil-teks').innerText  = 'Kunjungan DITERIMA!';
            document.getElementById('hasil-teks').className  = 'text-success';
        } else {
            document.getElementById('hasil-icon').innerText  = '❌';
            document.getElementById('hasil-teks').innerText  = 'Kunjungan DITOLAK!';
            document.getElementById('hasil-teks').className  = 'text-danger';
        }

        document.getElementById('hasil-detail').innerText =
            `Jarak: ${data.jarak} m | Threshold: ${data.threshold} m`;
    })
    .catch(() => alert('Gagal mengirim laporan!'));
}

function scanLagi() {
    document.getElementById('info-sales').style.display  = 'none';
    document.getElementById('status-sales').innerHTML    = '';
    mulaiScan();
}

// ── Fungsi getAccuratePosition (Lampiran 1 modul) ──
function getAccuratePosition(targetAccuracy = 50, maxWait = 20000) {
    return new Promise((resolve, reject) => {
        let bestResult = null;
        const startTime = Date.now();

        const watchId = navigator.geolocation.watchPosition(
            (position) => {
                const acc = position.coords.accuracy;

                if (!bestResult || acc < bestResult.coords.accuracy) {
                    bestResult = position;
                    const statusEl = document.getElementById('status-sales');
                    if (statusEl) statusEl.innerHTML =
                        `⏳ Mencari lokasi terbaik... accuracy: <strong>${Math.round(acc)}m</strong>`;
                }

                if (acc <= targetAccuracy) {
                    navigator.geolocation.clearWatch(watchId);
                    resolve(bestResult);
                }

                if (Date.now() - startTime >= maxWait) {
                    navigator.geolocation.clearWatch(watchId);
                    if (bestResult) resolve(bestResult);
                    else reject(new Error('Timeout, tidak dapat posisi'));
                }
            },
            (error) => reject(error),
            { enableHighAccuracy: true, maximumAge: 0, timeout: maxWait }
        );
    });
}
</script>
@endpush