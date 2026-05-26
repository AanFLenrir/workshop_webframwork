@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">

        {{-- ===== CARD 1: SELECT BIASA ===== --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4 fw-semibold">
                    Select
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kota</label>
                        <input type="text" id="inputKota1" class="form-control" placeholder="Nama kota...">
                    </div>
                    <button type="button" class="btn btn-primary w-100 mb-3" onclick="tambahKota(1)">
                        Tambahkan
                    </button>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Kota</label>
                        <select id="selectKota1" class="form-select" onchange="pilihKota(1)">
                            <option value="">-- Pilih Kota --</option>
                        </select>
                    </div>
                    <p class="mb-0">Kota Terpilih: <strong id="kotaTerpilih1" class="text-primary">-</strong></p>
                </div>
            </div>
        </div>

        {{-- ===== CARD 2: SELECT2 ===== --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-success text-white rounded-top-4 fw-semibold">
                    Select 2
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kota</label>
                        <input type="text" id="inputKota2" class="form-control" placeholder="Nama kota...">
                    </div>
                    <button type="button" class="btn btn-success w-100 mb-3" onclick="tambahKota(2)">
                        Tambahkan
                    </button>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Kota</label>
                        <select id="selectKota2" class="form-select" style="width:100%">
                            <option value="">-- Pilih Kota --</option>
                        </select>
                    </div>
                    <p class="mb-0">Kota Terpilih: <strong id="kotaTerpilih2" class="text-success">-</strong></p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {
    // Init Select2
    $('#selectKota2').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Pilih Kota --',
        allowClear: true,
    });

    // Event Select2
    $('#selectKota2').on('change', function () {
        const val = $(this).val();
        document.getElementById('kotaTerpilih2').textContent = val || '-';
    });
});

function tambahKota(card) {
    const input  = document.getElementById(`inputKota${card}`);
    const select = document.getElementById(`selectKota${card}`);
    const nama   = input.value.trim();

    if (!nama) {
        input.focus();
        return;
    }

    // Tambah option ke select
    const option = document.createElement('option');
    option.value       = nama;
    option.textContent = nama;
    select.appendChild(option);

    // Kalau card 2, refresh Select2
    if (card === 2) {
        $('#selectKota2').trigger('change');
    }

    input.value = '';
    input.focus();
}

function pilihKota(card) {
    const select = document.getElementById(`selectKota${card}`);
    const hasil  = document.getElementById(`kotaTerpilih${card}`);
    hasil.textContent = select.value || '-';
}
</script>
@endpush
