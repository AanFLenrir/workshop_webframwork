@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white fw-semibold rounded-top-4">
                    <i class="mdi mdi-camera me-2"></i>Tambah Customer 1 — Foto sebagai Blob
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif
                    <div class="mb-3 text-center">
                        <video id="video" autoplay playsinline
                               class="rounded-3 border w-100"
                               style="max-height:300px;background:#000;"></video>
                        <canvas id="canvas" style="display:none;"></canvas>
                    </div>
                    <div class="mb-3 text-center" id="previewContainer" style="display:none;">
                        <img id="previewImg" src="" alt="preview"
                             class="rounded-3 border"
                             style="max-width:100%;max-height:250px;object-fit:cover;">
                    </div>
                    <div class="d-flex gap-2 mb-4">
                        <button type="button" id="btnCapture" class="btn btn-primary w-100">
                            <i class="mdi mdi-camera me-1"></i> Ambil Foto
                        </button>
                        <button type="button" id="btnRetake" class="btn btn-outline-secondary w-100"
                                style="display:none;">
                            <i class="mdi mdi-refresh me-1"></i> Ulangi
                        </button>
                    </div>
                    <form action="{{ route('customer.store1') }}" method="POST" id="form1">
                        @csrf
                        <input type="hidden" name="photo" id="photoData">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Customer</label>
                            <input type="text" name="name" class="form-control"
                                   placeholder="Masukkan nama..." value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">No. Telepon</label>
                            <input type="text" name="phone" class="form-control"
                                   placeholder="Masukkan nomor telepon..." value="{{ old('phone') }}">
                        </div>
                        <button type="submit" id="btnSimpan" class="btn btn-success w-100" disabled>
                            <i class="mdi mdi-content-save me-1"></i> Simpan Customer
                        </button>
                    </form>
                    <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                        ← Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const video       = document.getElementById('video');
const canvas      = document.getElementById('canvas');
const previewImg  = document.getElementById('previewImg');
const previewCont = document.getElementById('previewContainer');
const btnCapture  = document.getElementById('btnCapture');
const btnRetake   = document.getElementById('btnRetake');
const btnSimpan   = document.getElementById('btnSimpan');
const photoData   = document.getElementById('photoData');

navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false })
    .then(stream => { video.srcObject = stream; })
    .catch(err => { alert('Tidak dapat mengakses kamera: ' + err.message); });

btnCapture.addEventListener('click', function () {
    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
    photoData.value = dataUrl;
    previewImg.src = dataUrl;
    previewCont.style.display = 'block';
    video.style.display = 'none';
    btnCapture.style.display = 'none';
    btnRetake.style.display  = 'block';
    btnSimpan.disabled = false;
});

btnRetake.addEventListener('click', function () {
    photoData.value = '';
    previewCont.style.display = 'none';
    video.style.display = 'block';
    btnCapture.style.display = 'block';
    btnRetake.style.display  = 'none';
    btnSimpan.disabled = true;
});
</script>
@endpush
