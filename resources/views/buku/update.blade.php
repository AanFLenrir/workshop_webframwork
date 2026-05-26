@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Edit Buku</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="formUpdateBuku" action="{{ route('buku.update', $buku->idbuku) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Kode Buku</label>
                        <input type="text" name="kode"
                               class="form-control"
                               value="{{ old('kode', $buku->kode) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Judul</label>
                        <input type="text" name="judul"
                               class="form-control"
                               value="{{ old('judul', $buku->judul) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Pengarang</label>
                        <input type="text" name="pengarang"
                               class="form-control"
                               value="{{ old('pengarang', $buku->pengarang) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="idkategori" class="form-control" required>
                            @foreach($kategori as $k)
                                <option value="{{ $k->idkategori }}"
                                    {{ $buku->idkategori == $k->idkategori ? 'selected' : '' }}>
                                    {{ $k->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </form>

                <button type="button" id="btnUpdateBuku" class="btn btn-primary" onclick="submitForm('formUpdateBuku', 'btnUpdateBuku', 'Update')">
                    Update
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