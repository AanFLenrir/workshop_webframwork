@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Edit Kategori</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="formUpdateKategori" action="{{ route('kategori.update', $kategori->idkategori) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input type="text"
                               name="nama_kategori"
                               class="form-control"
                               value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                    </div>

                </form>

                <button type="button" id="btnUpdateKategori" class="btn btn-primary" onclick="submitForm('formUpdateKategori', 'btnUpdateKategori')">
                    Update
                </button>
                <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Kembali</a>

            </div>
        </div>
    </div>
</div>

<script>
function submitForm(formId, btnId) {
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