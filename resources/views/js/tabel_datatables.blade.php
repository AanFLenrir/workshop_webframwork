@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-success text-white rounded-top-4 fw-semibold">
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
                                <button type="button" id="btnSimpan" class="btn btn-success w-100" onclick="tambahBarang()">
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
                <div class="card-header bg-white border-bottom fw-semibold text-success">
                    <i class="fas fa-table me-2"></i>Data Barang (DataTables)
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover align-middle w-100" id="tabelBarang">
                        <thead class="table-success">
                            <tr><th>ID Barang</th><th>Nama</th><th>Harga</th></tr>
                        </thead>
                        <tbody></tbody>
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
            <div class="modal-header bg-success text-white">
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
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
let counter = 1;
let dt;
let selectedRowIdx = null;

$(document).ready(function () {
    dt = $('#tabelBarang').DataTable({
        language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json' },
        columns: [{ title: 'ID Barang' }, { title: 'Nama' }, { title: 'Harga' }],
        data: [],
        createdRow: function(row) {
            $(row).css('cursor', 'pointer');
            $(row).on('click', function() {
                const rowData = dt.row(this).data();
                const idx     = dt.row(this).index();
                selectedRowIdx = idx;
                document.getElementById('modalId').value    = $(rowData[0]).text() || rowData[0];
                document.getElementById('modalNama').value  = rowData[1];
                document.getElementById('modalHarga').value = $(rowData[2]).text().replace(/[^0-9]/g,'') || rowData[2];
                document.getElementById('btnHapusModal').disabled = false;
                document.getElementById('btnHapusModal').innerHTML = '<i class="fas fa-trash me-1"></i>Hapus';
                document.getElementById('btnUbahModal').disabled = false;
                document.getElementById('btnUbahModal').innerHTML = '<i class="fas fa-save me-1"></i>Ubah';
                new bootstrap.Modal(document.getElementById('modalAksi')).show();
            });
        }
    });
});

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
        dt.row.add([
            `<code>${id}</code>`,
            nama.value,
            `<span class="fw-semibold text-success">Rp ${parseInt(harga.value).toLocaleString('id-ID')}</span>`
        ]).draw();
        counter++;
        nama.value = ''; harga.value = '';
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-2"></i>Submit';
    }, 500);
}

function ubahRow() {
    const form = document.getElementById('formModal');
    if (!form.checkValidity()) { form.reportValidity(); return; }
    const btn = document.getElementById('btnUbahModal');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    setTimeout(function () {
        const id    = document.getElementById('modalId').value;
        const nama  = document.getElementById('modalNama').value;
        const harga = document.getElementById('modalHarga').value;
        dt.row(selectedRowIdx).data([
            `<code>${id}</code>`,
            nama,
            `<span class="fw-semibold text-success">Rp ${parseInt(harga).toLocaleString('id-ID')}</span>`
        ]).draw();
        bootstrap.Modal.getInstance(document.getElementById('modalAksi')).hide();
    }, 500);
}

function hapusRow() {
    const btn = document.getElementById('btnHapusModal');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    setTimeout(function () {
        dt.row(selectedRowIdx).remove().draw();
        bootstrap.Modal.getInstance(document.getElementById('modalAksi')).hide();
    }, 500);
}
</script>
@endpush
