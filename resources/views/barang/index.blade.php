@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- ===== FORM TAMBAH ===== --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4 fw-semibold">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Barang
                </div>
                <div class="card-body">
                    <form action="{{ route('barang.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Barang</label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                   maxlength="50" placeholder="Nama barang..." value="{{ old('nama') }}" required>
                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Harga (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror"
                                       min="1" placeholder="0" value="{{ old('harga') }}" required>
                                @error('harga')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                    </form>
                </div>
            </div>

            {{-- INFO GRID KERTAS --}}
            <div class="card shadow-sm border-0 rounded-4 mt-4">
                <div class="card-header bg-secondary text-white rounded-top-4 fw-semibold">
                    <i class="fas fa-th me-2"></i>Kertas Label TnJ No.108
                </div>
                <div class="card-body text-center">
                    <p class="text-muted small mb-2">
                        Layout: <strong>5 kolom × 8 baris = 40 label/lembar</strong>
                    </p>
                    <div id="gridSidebar" style="display:grid;grid-template-columns:repeat(5,1fr);gap:3px;max-width:220px;margin:0 auto">
                        @for($r = 1; $r <= 8; $r++)
                            @for($c = 1; $c <= 5; $c++)
                                @php $n = ($r-1)*5+$c; @endphp
                                <div style="border:1px solid #dee2e6;border-radius:3px;height:22px;
                                            font-size:9px;display:flex;align-items:center;
                                            justify-content:center;background:#fff;color:#aaa">
                                    {{ $n }}
                                </div>
                            @endfor
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== TABEL DATA ===== --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white rounded-top-4 d-flex align-items-center justify-content-between border-bottom">
                    <span class="fw-semibold text-primary">
                        <i class="fas fa-tags me-2"></i>Data Barang
                    </span>
                    <button id="btnCetakTerpilih" class="btn btn-success btn-sm d-none"
                            data-bs-toggle="modal" data-bs-target="#modalCetak">
                        <i class="fas fa-print me-1"></i>Cetak Terpilih
                        (<span id="jumlahTerpilih">0</span>)
                    </button>
                </div>
                <div class="card-body">
                    <table id="tblBarang" class="table table-hover align-middle w-100">
                        <thead class="table-light">
                            <tr>
                                <th style="width:40px">
                                    <input type="checkbox" id="checkAll" class="form-check-input">
                                </th>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Waktu Input</th>
                                <th style="width:110px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barangs as $b)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input chk-barang"
                                           value="{{ $b->id_barang }}"
                                           data-nama="{{ $b->nama }}"
                                           data-harga="{{ $b->harga }}">
                                </td>
                                <td>
                                    <code class="bg-light px-2 py-1 rounded small">{{ $b->id_barang }}</code>
                                </td>
                                <td>{{ $b->nama }}</td>
                                <td class="fw-semibold text-success">
                                    Rp {{ number_format($b->harga, 0, ',', '.') }}
                                </td>
                                <td class="text-muted small">{{ $b->tgl_input }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm me-1"
                                            onclick="openEdit('{{ $b->id_barang }}','{{ addslashes($b->nama) }}',{{ $b->harga }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm"
                                            onclick="confirmHapus('{{ $b->id_barang }}','{{ addslashes($b->nama) }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>{{-- /row --}}
</div>


{{-- ===== MODAL EDIT ===== --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <form id="formEdit" method="POST">
                @csrf @method('PUT')
                <div class="modal-header bg-warning">
                    <h5 class="modal-title fw-semibold"><i class="fas fa-edit me-2"></i>Edit Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">ID Barang</label>
                        <input type="text" id="editIdDisplay" class="form-control bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Barang</label>
                        <input type="text" name="nama" id="editNama" class="form-control" maxlength="50" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga" id="editHarga" class="form-control" min="1" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== MODAL HAPUS ===== --}}
<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4">
            <form id="formHapus" method="POST">
                @csrf @method('DELETE')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-semibold"><i class="fas fa-trash me-2"></i>Hapus Barang</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Hapus <strong id="hapusNama"></strong>?</p>
                    <p class="text-muted small">Tindakan ini tidak bisa dibatalkan.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash me-1"></i>Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== MODAL CETAK ===== --}}
<div class="modal fade" id="modalCetak" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-semibold"><i class="fas fa-print me-2"></i>Cetak Tag Harga</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4 align-items-start">

                    {{-- Kiri: koordinat --}}
                    <div class="col-md-5">
                        <h6 class="fw-semibold"><i class="fas fa-crosshairs me-2 text-success"></i>Koordinat Awal Cetak</h6>
                        <p class="text-muted small">Tentukan posisi label <strong>pertama</strong> di kertas.
                           Berguna jika sebagian label sudah terpakai sebelumnya.</p>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold small">Kolom X (1–5)</label>
                                <input type="number" id="inputX" class="form-control" min="1" max="5" value="1">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold small">Baris Y (1–8)</label>
                                <input type="number" id="inputY" class="form-control" min="1" max="8" value="1">
                            </div>
                        </div>
                        <div class="alert alert-info small py-2">
                            <i class="fas fa-lightbulb me-1"></i>
                            Mulai cetak dari label ke-<strong id="nomorLabel">1</strong>
                        </div>

                        <h6 class="fw-semibold mt-3 mb-2 small">
                            <i class="fas fa-tag me-1 text-primary"></i>Barang yang Dipilih:
                        </h6>
                        <ul id="listTerpilih" class="list-group list-group-flush small"
                            style="max-height:180px;overflow-y:auto"></ul>
                    </div>

                    {{-- Kanan: preview grid --}}
                    <div class="col-md-7">
                        <h6 class="fw-semibold text-center mb-3">
                            <i class="fas fa-th me-2 text-secondary"></i>Preview Posisi di Kertas
                        </h6>
                        <div id="gridModal"
                             style="display:grid;grid-template-columns:repeat(5,1fr);gap:4px;max-width:320px;margin:0 auto">
                            @for($r = 1; $r <= 8; $r++)
                                @for($c = 1; $c <= 5; $c++)
                                    @php $n = ($r-1)*5+$c; @endphp
                                    <div id="mc-{{ $r }}-{{ $c }}"
                                         style="border:1px solid #dee2e6;border-radius:4px;height:34px;
                                                font-size:9px;display:flex;align-items:center;
                                                justify-content:center;background:#fff;color:#aaa">
                                        {{ $n }}
                                    </div>
                                @endfor
                            @endfor
                        </div>
                        <p class="text-center text-muted small mt-2">
                            <span class="badge bg-success">■</span> Mulai cetak &nbsp;
                            <span class="badge bg-primary">■</span> Label berikutnya &nbsp;
                            <span class="badge bg-light text-dark border">■</span> Kosong
                        </p>
                    </div>

                </div>
            </div>

            {{-- ↓ PERUBAHAN 1: Tambah tombol "Dengan Barcode" --}}
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" onclick="doCetak()">
                    <i class="fas fa-file-pdf me-2"></i>Tanpa Barcode
                </button>
                <button type="button" class="btn btn-dark" onclick="doCetakBarcode()">
                    <i class="fas fa-barcode me-2"></i>Dengan Barcode
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Form hidden untuk submit cetak (tanpa barcode) --}}
<form id="formCetak" method="POST" action="{{ route('barang.cetakPdf') }}" target="_blank">
    @csrf
    <input type="hidden" name="ids"    id="cetakIds">
    <input type="hidden" name="coordX" id="cetakX">
    <input type="hidden" name="coordY" id="cetakY">
</form>

{{-- ↓ PERUBAHAN 2: Form hidden untuk submit cetak dengan barcode --}}
<form id="formCetakBarcode" method="POST" action="{{ route('barang.cetakPdfBarcode') }}" target="_blank">
    @csrf
    <input type="hidden" name="ids"    id="cetakIdsBarcode">
    <input type="hidden" name="coordX" id="cetakXBarcode">
    <input type="hidden" name="coordY" id="cetakYBarcode">
</form>

@endsection

@push('scripts')
{{-- DataTables --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<script>
$(document).ready(function () {
    $('#tblBarang').DataTable({
        language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json' },
        columnDefs: [{ orderable: false, targets: [0, 5] }],
        pageLength: 10,
    });
});

// ── Checkbox ──────────────────────────────────
document.getElementById('checkAll').addEventListener('change', function () {
    document.querySelectorAll('.chk-barang').forEach(c => c.checked = this.checked);
    updateCetakBtn();
});
document.querySelectorAll('.chk-barang').forEach(c =>
    c.addEventListener('change', updateCetakBtn)
);

function updateCetakBtn() {
    const n = document.querySelectorAll('.chk-barang:checked').length;
    document.getElementById('jumlahTerpilih').textContent = n;
    document.getElementById('btnCetakTerpilih').classList.toggle('d-none', n === 0);
}

// ── Modal Edit ─────────────────────────────────
function openEdit(id, nama, harga) {
    document.getElementById('editIdDisplay').value = id;
    document.getElementById('editNama').value      = nama;
    document.getElementById('editHarga').value     = harga;
    document.getElementById('formEdit').action     = `/barang/${id}`;
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}

// ── Modal Hapus ────────────────────────────────
function confirmHapus(id, nama) {
    document.getElementById('hapusNama').textContent = nama;
    document.getElementById('formHapus').action      = `/barang/${id}`;
    new bootstrap.Modal(document.getElementById('modalHapus')).show();
}

// ── Preview Grid Modal Cetak ───────────────────
function updatePreview() {
    const x = Math.min(5, Math.max(1, parseInt(document.getElementById('inputX').value) || 1));
    const y = Math.min(8, Math.max(1, parseInt(document.getElementById('inputY').value) || 1));
    const startIdx = (y - 1) * 5 + x; // 1-based nomor label

    document.getElementById('nomorLabel').textContent = startIdx;

    const checked = [...document.querySelectorAll('.chk-barang:checked')];

    // Reset semua cell
    for (let r = 1; r <= 8; r++) {
        for (let c = 1; c <= 5; c++) {
            const el = document.getElementById(`mc-${r}-${c}`);
            el.style.cssText = 'border:1px solid #dee2e6;border-radius:4px;height:34px;' +
                               'font-size:9px;display:flex;align-items:center;' +
                               'justify-content:center;background:#fff;color:#aaa';
            el.textContent = (r - 1) * 5 + c;
        }
    }

    // Warnai slot yang akan diisi
    checked.forEach((chk, i) => {
        const slot = startIdx + i; // 1-based
        if (slot > 40) return;
        const r = Math.ceil(slot / 5);
        const c = slot - (r - 1) * 5;
        const el = document.getElementById(`mc-${r}-${c}`);
        if (i === 0) {
            el.style.background = '#198754'; el.style.color = '#fff'; el.style.borderColor = '#198754';
        } else {
            el.style.background = '#cfe2ff'; el.style.color = '#0d6efd'; el.style.borderColor = '#0d6efd';
        }
        el.textContent = chk.dataset.nama.substring(0, 4) + '..';
    });

    // Update list terpilih
    const ul = document.getElementById('listTerpilih');
    ul.innerHTML = '';
    if (checked.length === 0) {
        ul.innerHTML = '<li class="list-group-item text-muted text-center small">Tidak ada barang dipilih</li>';
        return;
    }
    checked.forEach((c, i) => {
        const slotNo = startIdx + i;
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center py-1 px-2';
        li.innerHTML = `<span class="small">${i + 1}. ${c.dataset.nama}</span>
            <span class="badge ${slotNo > 40 ? 'bg-danger' : 'bg-success'} rounded-pill small">
                ${slotNo <= 40 ? '#' + slotNo : 'Melebihi!'}
            </span>`;
        ul.appendChild(li);
    });
}

document.getElementById('modalCetak').addEventListener('show.bs.modal', updatePreview);
document.getElementById('inputX').addEventListener('input', updatePreview);
document.getElementById('inputY').addEventListener('input', updatePreview);

// ── Submit Cetak PDF (tanpa barcode) ───────────
function doCetak() {
    const checked = [...document.querySelectorAll('.chk-barang:checked')];
    if (checked.length === 0) { alert('Pilih minimal 1 barang!'); return; }

    document.getElementById('cetakIds').value = checked.map(c => c.value).join(',');
    document.getElementById('cetakX').value   = document.getElementById('inputX').value;
    document.getElementById('cetakY').value   = document.getElementById('inputY').value;
    document.getElementById('formCetak').submit();

    bootstrap.Modal.getInstance(document.getElementById('modalCetak')).hide();
}

// ↓ PERUBAHAN 3: Fungsi submit cetak dengan barcode
// ── Submit Cetak PDF (dengan barcode) ─────────
function doCetakBarcode() {
    const checked = [...document.querySelectorAll('.chk-barang:checked')];
    if (checked.length === 0) { alert('Pilih minimal 1 barang!'); return; }

    document.getElementById('cetakIdsBarcode').value = checked.map(c => c.value).join(',');
    document.getElementById('cetakXBarcode').value   = document.getElementById('inputX').value;
    document.getElementById('cetakYBarcode').value   = document.getElementById('inputY').value;
    document.getElementById('formCetakBarcode').submit();

    bootstrap.Modal.getInstance(document.getElementById('modalCetak')).hide();
}
</script>
@endpush