@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-success text-white rounded-top-4 fw-semibold">
                    <i class="fas fa-map-marker-alt me-2"></i>Wilayah Administrasi Indonesia (Axios)
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Provinsi</label>
                        <select id="provinsi" class="form-select"><option value="">-- Pilih Provinsi --</option></select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kota / Kabupaten</label>
                        <select id="kota" class="form-select" disabled><option value="">-- Pilih Kota --</option></select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kecamatan</label>
                        <select id="kecamatan" class="form-select" disabled><option value="">-- Pilih Kecamatan --</option></select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kelurahan</label>
                        <select id="kelurahan" class="form-select" disabled><option value="">-- Pilih Kelurahan --</option></select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
window.addEventListener('DOMContentLoaded', function () {
    axios.get('{{ route("api.provinsi") }}').then(function (res) {
        res.data.forEach(function (item) {
            document.getElementById('provinsi').innerHTML += '<option value="' + item.id + '">' + item.name + '</option>';
        });
    });
});
document.getElementById('provinsi').addEventListener('change', function () {
    const id = this.value;
    document.getElementById('kota').innerHTML = '<option value="">-- Pilih Kota --</option>';
    document.getElementById('kota').disabled = true;
    document.getElementById('kecamatan').innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
    document.getElementById('kecamatan').disabled = true;
    document.getElementById('kelurahan').innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
    document.getElementById('kelurahan').disabled = true;
    if (!id) return;
    axios.get('/api/kota/' + id).then(function (res) {
        const el = document.getElementById('kota');
        el.disabled = false;
        res.data.forEach(function (item) { el.innerHTML += '<option value="' + item.id + '">' + item.name + '</option>'; });
    });
});
document.getElementById('kota').addEventListener('change', function () {
    const id = this.value;
    document.getElementById('kecamatan').innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
    document.getElementById('kecamatan').disabled = true;
    document.getElementById('kelurahan').innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
    document.getElementById('kelurahan').disabled = true;
    if (!id) return;
    axios.get('/api/kecamatan/' + id).then(function (res) {
        const el = document.getElementById('kecamatan');
        el.disabled = false;
        res.data.forEach(function (item) { el.innerHTML += '<option value="' + item.id + '">' + item.name + '</option>'; });
    });
});
document.getElementById('kecamatan').addEventListener('change', function () {
    const id = this.value;
    document.getElementById('kelurahan').innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
    document.getElementById('kelurahan').disabled = true;
    if (!id) return;
    axios.get('/api/kelurahan/' + id).then(function (res) {
        const el = document.getElementById('kelurahan');
        el.disabled = false;
        res.data.forEach(function (item) { el.innerHTML += '<option value="' + item.id + '">' + item.name + '</option>'; });
    });
});
</script>
@endpush
