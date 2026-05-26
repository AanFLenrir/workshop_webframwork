@extends('layouts.app')

@section('content')
<div class="row mt-3">

    {{-- HEADER --}}
    <div class="col-12 mb-3">
        <div class="card bg-primary text-white shadow">
            <div class="card-body d-flex justify-content-between align-items-center py-3">
                <div>
                    <h4 class="mb-0"><i class="mdi mdi-hospital-box-outline"></i> Admin Antrian</h4>
                    <small>Semua Poli — Real-time</small>
                </div>
                <button id="btnPanggil" class="btn btn-warning btn-lg fw-bold px-4">
                    <i class="mdi mdi-bullhorn"></i> Panggil Berikutnya
                </button>
            </div>
        </div>
    </div>

    {{-- SEDANG DIPANGGIL --}}
    <div class="col-md-4 mb-3">
        <div class="card shadow h-100 border-0">
            <div class="card-header bg-success text-white fw-bold">
                <i class="mdi mdi-account-voice"></i> Sedang Dipanggil
            </div>
            <div class="card-body text-center py-4">
                <div id="nomorDipanggil" style="font-size:80px; font-weight:bold; color:#4B49AC; line-height:1">—</div>
                <h5 id="namaDipanggil" class="text-muted mt-2">Belum ada</h5>
                <span id="poliDipanggil" class="badge bg-primary"></span>
            </div>
        </div>
    </div>

    {{-- ANTRIAN MENUNGGU --}}
    <div class="col-md-4 mb-3">
        <div class="card shadow h-100 border-0">
            <div class="card-header bg-warning text-dark fw-bold">
                <i class="mdi mdi-account-clock"></i> Menunggu
                <span id="jumlahWaiting" class="badge bg-dark ms-2">0</span>
            </div>
            <div class="card-body p-0" style="max-height:450px; overflow-y:auto;">
                <table class="table table-hover mb-0">
                    <tbody id="listWaiting"></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ANTRIAN TERLAMBAT --}}
    <div class="col-md-4 mb-3">
        <div class="card shadow h-100 border-0">
            <div class="card-header bg-danger text-white fw-bold">
                <i class="mdi mdi-account-alert"></i> Terlambat
                <span id="jumlahTerlambat" class="badge bg-dark ms-2">0</span>
            </div>
            <div class="card-body p-0" style="max-height:450px; overflow-y:auto;">
                <table class="table table-hover mb-0">
                    <tbody id="listTerlambat"></tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
const csrfToken = '{{ csrf_token() }}';

function updateUI(data) {
    if (data.sedang_dipanggil) {
        document.getElementById('nomorDipanggil').textContent = data.sedang_dipanggil.nomor;
        document.getElementById('namaDipanggil').textContent  = data.sedang_dipanggil.nama;
        document.getElementById('poliDipanggil').textContent  = data.sedang_dipanggil.poli?.nama ?? '';
    } else {
        document.getElementById('nomorDipanggil').textContent = '—';
        document.getElementById('namaDipanggil').textContent  = 'Belum ada';
        document.getElementById('poliDipanggil').textContent  = '';
    }

    const waiting = data.antrian ?? [];
    document.getElementById('jumlahWaiting').textContent = waiting.length;
    document.getElementById('listWaiting').innerHTML = waiting.map(a => `
        <tr>
            <td class="ps-3"><span class="badge bg-warning text-dark fs-6">${a.nomor}</span></td>
            <td>
                <strong>${a.nama}</strong><br>
                <small class="text-muted"><i class="mdi mdi-hospital-box-outline"></i> ${a.poli?.nama ?? ''}</small>
            </td>
            <td class="text-end pe-3">
                <button class="btn btn-sm btn-outline-danger" onclick="lewati(${a.id})" title="Lewati">
                    <i class="mdi mdi-account-arrow-right"></i>
                </button>
            </td>
        </tr>
    `).join('') || '<tr><td colspan="3" class="text-center text-muted p-3">Tidak ada antrian</td></tr>';

    const terlambat = data.terlambat ?? [];
    document.getElementById('jumlahTerlambat').textContent = terlambat.length;
    document.getElementById('listTerlambat').innerHTML = terlambat.map(a => `
        <tr>
            <td class="ps-3"><span class="badge bg-danger fs-6">${a.nomor}</span></td>
            <td>
                <strong>${a.nama}</strong><br>
                <small class="text-muted"><i class="mdi mdi-hospital-box-outline"></i> ${a.poli?.nama ?? ''}</small>
            </td>
            <td class="text-end pe-3">
                <button class="btn btn-sm btn-outline-primary" ondblclick="panggilLagi(${a.id})" title="Double click untuk panggil ulang">
                    <i class="mdi mdi-phone-return"></i>
                </button>
            </td>
        </tr>
    `).join('') || '<tr><td colspan="3" class="text-center text-muted p-3">Tidak ada</td></tr>';
}

function pollData() {
    fetch('/sse/antrian')
        .then(r => r.json())
        .then(data => updateUI(data))
        .catch(err => console.warn('Poll error:', err));
}
pollData();
setInterval(pollData, 2000);

document.getElementById('btnPanggil').addEventListener('click', function() {
    fetch('/admin/panggil', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json' }
    }).then(r => r.json()).then(data => {
        if (data.message === 'Tidak ada antrian') alert('Tidak ada antrian yang menunggu!');
        pollData();
    });
});

function lewati(id) {
    if (!confirm('Tandai sebagai terlambat?')) return;
    fetch(`/admin/lewati/${id}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    }).then(() => pollData());
}

function panggilLagi(id) {
    fetch(`/admin/panggilLagi/${id}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    }).then(() => pollData());
}
</script>
@endsection
