@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4 fw-semibold">
                    <i class="fas fa-map-marker-alt me-2"></i>Wilayah Administrasi Indonesia (Ajax jQuery)
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
<script>
$(document).ready(function () {
    $('#provinsi').html('<option value="">-- Pilih Provinsi --</option>');
    $.ajax({ url: '/api/provinsi', type: 'GET',
        success: function (data) {
            $.each(data, function (i, item) {
                $('#provinsi').append('<option value="' + item.id + '">' + item.name + '</option>');
            });
        }
    });

    $('#provinsi').on('change', function () {
        const id = $(this).val();
        $('#kota').html('<option value="">-- Pilih Kota --</option>').prop('disabled', true);
        $('#kecamatan').html('<option value="">-- Pilih Kecamatan --</option>').prop('disabled', true);
        $('#kelurahan').html('<option value="">-- Pilih Kelurahan --</option>').prop('disabled', true);
        if (!id) return;
        $.ajax({ url: '/api/kota/' + id, type: 'GET',
            success: function (data) {
                $('#kota').prop('disabled', false);
                $.each(data, function (i, item) {
                    $('#kota').append('<option value="' + item.id + '">' + item.name + '</option>');
                });
            }
        });
    });

    $('#kota').on('change', function () {
        const id = $(this).val();
        $('#kecamatan').html('<option value="">-- Pilih Kecamatan --</option>').prop('disabled', true);
        $('#kelurahan').html('<option value="">-- Pilih Kelurahan --</option>').prop('disabled', true);
        if (!id) return;
        $.ajax({ url: '/api/kecamatan/' + id, type: 'GET',
            success: function (data) {
                $('#kecamatan').prop('disabled', false);
                $.each(data, function (i, item) {
                    $('#kecamatan').append('<option value="' + item.id + '">' + item.name + '</option>');
                });
            }
        });
    });

    $('#kecamatan').on('change', function () {
        const id = $(this).val();
        $('#kelurahan').html('<option value="">-- Pilih Kelurahan --</option>').prop('disabled', true);
        if (!id) return;
        $.ajax({ url: '/api/kelurahan/' + id, type: 'GET',
            success: function (data) {
                $('#kelurahan').prop('disabled', false);
                $.each(data, function (i, item) {
                    $('#kelurahan').append('<option value="' + item.id + '">' + item.name + '</option>');
                });
            }
        });
    });
});
</script>
@endpush
