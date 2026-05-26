@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-md-11">

        {{-- STEP 1: Pilih Poli --}}
        <div id="stepPoli">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="mdi mdi-hospital-box-outline"></i> Pendaftaran Antrian — Pilih Poli</h4>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        @foreach($polis as $poli)
                        <div class="col-md-3 col-6">
                            <div class="card border-2 text-center p-4 h-100 poli-card"
                                 onclick="pilihPoli({{ $poli->id }}, '{{ $poli->nama }}')"
                                 style="cursor:pointer; border-color:#e0e0e0; transition:all 0.2s;">
                                <i class="mdi mdi-hospital-box-outline" style="font-size:52px; color:#4B49AC;"></i>
                                <div class="fw-bold mt-3" style="font-size:1rem;">{{ $poli->nama }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- STEP 2: Isi Nama --}}
        <div id="stepNama" style="display:none;">
            <div class="card shadow border-0" style="max-width:500px; margin:0 auto;">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <button onclick="kembali()" class="btn btn-sm btn-light me-3">
                        <i class="mdi mdi-arrow-left"></i>
                    </button>
                    <h5 class="mb-0"><i class="mdi mdi-ticket-outline"></i> Daftar — <span id="namaPoliDipilih"></span></h5>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="/guest/daftar" method="POST">
                        @csrf
                        <input type="hidden" name="poli_id" id="inputPoliId">

                        <div class="text-center mb-4">
                            <i class="mdi mdi-hospital-box-outline" style="font-size:70px; color:#4B49AC;"></i>
                            <h5 class="mt-2 fw-bold" id="namaPoliForm"></h5>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Nama Pasien</label>
                            <input type="text" name="nama" class="form-control form-control-lg"
                                   placeholder="Masukkan nama lengkap" required autofocus>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                            <i class="mdi mdi-ticket-outline"></i> Ambil Nomor Antrian
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
.poli-card:hover {
    border-color: #4B49AC !important;
    background: #f0efff;
    transform: translateY(-4px);
    box-shadow: 0 6px 20px rgba(75,73,172,0.2);
}
</style>

<script>
function pilihPoli(id, nama) {
    document.getElementById('inputPoliId').value = id;
    document.getElementById('namaPoliDipilih').textContent = nama;
    document.getElementById('namaPoliForm').textContent = nama;
    document.getElementById('stepPoli').style.display = 'none';
    document.getElementById('stepNama').style.display = 'block';
}

function kembali() {
    document.getElementById('stepPoli').style.display = 'block';
    document.getElementById('stepNama').style.display = 'none';
}

@if($errors->any())
    document.getElementById('stepPoli').style.display = 'none';
    document.getElementById('stepNama').style.display = 'block';
    document.getElementById('namaPoliDipilih').textContent = '{{ old("poli_id") }}';
    document.getElementById('inputPoliId').value = '{{ old("poli_id") }}';
@endif
</script>
@endsection
