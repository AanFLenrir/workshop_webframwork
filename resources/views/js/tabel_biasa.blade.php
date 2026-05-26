@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4 fw-semibold">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Barang
                </div>
                <div class="card-body">
                    <form id="formTambah">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Nama Barang</label>
                                <input type="text" id="inputNama" class="form-control" placeholder="Nama barang..." required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Harga Barang (Rp)</label>
                                <input type="number" id="inputHarga" class="form-control" placeholder="0" min="1" required>
                            </div>
                            <div class="col-md-3">
                                <button type="button" id="btnSimpan" class="btn btn-primary w-100" onclick="tambahBarang()">
                                    <i class="fas fa-save me-2"></i>Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-bottom fw-semibold text-primary">
                    <i class="fas fa-table me-2"></i>Data Barang (Tabel HTML Biasa)
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover align-middle" id="tabelBarang" style="cursor:pointer">
                        <thead class="table-primary">
                            <tr><th>ID Barang</th><th>Nama</th><th>Harga</th></tr>
                        </thead>
                        <tbody id="tbodyBarang">
                            <tr id="emptyRow"><td colspan="3" class="text-center text-muted">Belum ada data</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT/HAPUS --}}
<div class="modal fade" id="modalAksi" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-semibold"><i class="fas fa-edit me-2"></i>Edit / Hapus Barang</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formModal">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">ID Barang</label>
                        <input type="text" id="modalId" class="form-control bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Barang</label>
                        <input type="text" id="modalNama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga Barang</label>
                        <input type="number" id="modalHarga" class="form-control" min="1" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" id="btnHapusModal" class="btn btn-danger" onclick="hapusRow()">
                    <i class="fas fa-trash me-1"></i>Hapus
                </button>
                <button type="button" id="btnUbahModal" class="btn btn-success" onclick="ubahRow()">
                    <i class="fas fa-save me-1"></i>Ubah
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script>
let counter = 1;
let selectedRow = null;

function tambahBarang() {
    const form  = document.getElementById('formTambah');
    const btn   = document.getElementById('btnSimpan');
    const nama  = document.getElementById('inputNama');
    const harga = document.getElementById('inputHarga');
    if (!form.checkValidity()) { form.reportValidity(); return; }
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    setTimeout(function () {
        const now = new Date();
        const id  = String(now.getFullYear()).slice(2) + String(now.getMonth()+1).padStart(2,'0') + String(now.getDate()).padStart(2,'0') + String(counter).padStart(2,'0');
        const emptyRow = document.getElementById('emptyRow');
        if (emptyRow) emptyRow.remove();
        const tr = document.createElement('tr');
        tr.style.cursor = 'pointer';
        tr.dataset.id    = id;
        tr.dataset.nama  = nama.value;
        tr.dataset.harga = harga.value;
        tr.innerHTML = `<td><code>${id}</code></td><td>${nama.value}</td><td class="fw-semibold text-success">Rp ${parseInt(harga.value).toLocaleString('id-ID')}</td>`;
        tr.addEventListener('click', function () { openModal(this); });
        document.getElementById('tbodyBarang').appendChild(tr);
        counter++;
        nama.value = ''; harga.value = '';
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-2"></i>Submit';
    }, 500);
}

function openModal(row) {
    selectedRow = row;
    document.getElementById('modalId').value    = row.dataset.id;
    document.getElementById('modalNama').value  = row.dataset.nama;
    document.getElementById('modalHarga').value = row.dataset.harga;
    // Reset button
    document.getElementById('btnHapusModal').disabled = false;
    document.getElementById('btnHapusModal').innerHTML = '<i class="fas fa-trash me-1"></i>Hapus';
    document.getElementById('btnUbahModal').disabled = false;
    document.getElementById('btnUbahModal').innerHTML = '<i class="fas fa-save me-1"></i>Ubah';
    new bootstrap.Modal(document.getElementById('modalAksi')).show();
}

function ubahRow() {
    const form = document.getElementById('formModal');
    if (!form.checkValidity()) { form.reportValidity(); return; }
    const btn = document.getElementById('btnUbahModal');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    setTimeout(function () {
        const nama  = document.getElementById('modalNama').value;
        const harga = document.getElementById('modalHarga').value;
        selectedRow.dataset.nama  = nama;
        selectedRow.dataset.harga = harga;
        selectedRow.cells[1].textContent = nama;
        selectedRow.cells[2].innerHTML   = `<span class="fw-semibold text-success">Rp ${parseInt(harga).toLocaleString('id-ID')}</span>`;
        bootstrap.Modal.getInstance(document.getElementById('modalAksi')).hide();
    }, 500);
}

function hapusRow() {
    const btn = document.getElementById('btnHapusModal');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    setTimeout(function () {
        selectedRow.remove();
        if (document.getElementById('tbodyBarang').rows.length === 0) {
            const tr = document.createElement('tr');
            tr.id = 'emptyRow';
            tr.innerHTML = '<td colspan="3" class="text-center text-muted">Belum ada data</td>';
            document.getElementById('tbodyBarang').appendChild(tr);
        }
        bootstrap.Modal.getInstance(document.getElementById('modalAksi')).hide();
    }, 500);
}
</script>
@endpush
