@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Tambah Buku</h4>

                {{-- Error Validation --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="formTambahBuku" action="{{ route('buku.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Kode Buku</label>
                        <input type="text" name="kode"
                               class="form-control"
                               value="{{ old('kode') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Judul</label>
                        <input type="text" name="judul"
                               class="form-control"
                               value="{{ old('judul') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Pengarang</label>
                        <input type="text" name="pengarang"
                               class="form-control"
                               value="{{ old('pengarang') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="idkategori" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $k)
                                <option value="{{ $k->idkategori }}"
                                    {{ old('idkategori') == $k->idkategori ? 'selected' : '' }}>
                                    {{ $k->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </form>

                <button type="button" id="btnSimpanBuku" class="btn btn-primary" onclick="submitForm('formTambahBuku', 'btnSimpanBuku', 'Simpan')">
                    Simpan
                </button>
                <a href="{{ route('buku.index') }}" class="btn btn-secondary">Kembali</a>

            </div>
        </div>
    </div>
</div>

<script>
function submitForm(formId, btnId, originalText) {
    const form = document.getElementById(formId);
    const btn  = document.getElementById(btnId);

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Memproses...';
    form.submit();
}
</script>

@endsection