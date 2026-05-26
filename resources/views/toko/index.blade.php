@extends('layouts.app')

@section('title', 'Data Toko')

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">🏪 Data Toko</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahToko">
            + Tambah Toko
        </button>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- TABEL TOKO --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Barcode</th>
                        <th>Nama Toko</th>
                        <th>Alamat</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Accuracy (m)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tokos as $i => $toko)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><code>{{ $toko->barcode }}</code></td>
                        <td>{{ $toko->nama_toko }}</td>
                        <td>{{ $toko->alamat ?? '-' }}</td>
                        <td>{{ $toko->latitude }}</td>
                        <td>{{ $toko->longitude }}</td>
                        <td>{{ $toko->accuracy }}</td>
                        <td>
                            <a href="{{ route('toko.barcode', $toko->id) }}"
                               class="btn btn-sm btn-warning" target="_blank">
                                🖨️ Cetak Barcode
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Belum ada data toko.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH TOKO --}}
<div class="modal fade" id="modalTambahToko" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🏪 Tambah Toko Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('toko.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Toko</label>
                        <input type="text" name="nama_toko" class="form-control" required placeholder="Contoh: Toko Maju Jaya">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap toko"></textarea>
                    </div>

                    <hr>
                    <p class="fw-bold mb-2">📍 Input Titik Awal Lokasi Toko</p>
                    <p class="text-muted small mb-3">Klik tombol di bawah untuk mengambil lokasi GPS toko secara otomatis, atau isi manual.</p>

                    <button type="button" class="btn btn-success mb-3" onclick="ambilLokasiToko()">
                        📡 Ambil Lokasi GPS Sekarang
                    </button>
                    <div id="status-lokasi" class="text-muted small mb-3"></div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Latitude</label>
                            <input type="number" name="latitude" id="input-lat" class="form-control"
                                   step="any" required placeholder="-6.2088">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Longitude</label>
                            <input type="number" name="longitude" id="input-lng" class="form-control"
                                   step="any" required placeholder="106.8456">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Accuracy (meter)</label>
                            <input type="number" name="accuracy" id="input-acc" class="form-control"
                                   step="any" required placeholder="50">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Toko</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function ambilLokasiToko() {
    const statusEl = document.getElementById('status-lokasi');
    statusEl.innerHTML = '⏳ Mengambil lokasi, harap tunggu...';

    getAccuratePosition(50, 20000)
        .then(pos => {
            document.getElementById('input-lat').value = pos.coords.latitude;
            document.getElementById('input-lng').value = pos.coords.longitude;
            document.getElementById('input-acc').value = Math.round(pos.coords.accuracy);
            statusEl.innerHTML = `✅ Lokasi berhasil diambil! Accuracy: <strong>${Math.round(pos.coords.accuracy)} meter</strong>`;
            statusEl.className = 'text-success small mb-3';
        })
        .catch(err => {
            statusEl.innerHTML = '❌ Gagal mengambil lokasi: ' + err.message;
            statusEl.className = 'text-danger small mb-3';
        });
}

// Fungsi getAccuratePosition dari modul (Lampiran 1)
function getAccuratePosition(targetAccuracy = 50, maxWait = 20000) {
    return new Promise((resolve, reject) => {
        let bestResult = null;
        const startTime = Date.now();

        const watchId = navigator.geolocation.watchPosition(
            (position) => {
                const acc = position.coords.accuracy;

                if (!bestResult || acc < bestResult.coords.accuracy) {
                    bestResult = position;
                    document.getElementById('status-lokasi').innerHTML =
                        `⏳ Mencari lokasi terbaik... accuracy saat ini: <strong>${Math.round(acc)}m</strong>`;
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