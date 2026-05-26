@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Data Kategori</h4>

                {{-- Alert Success --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Tombol Tambah (Admin Only) --}}
                @if(auth()->user()->role == 'admin')
                    <a href="{{ route('kategori.create') }}" class="btn btn-primary mb-3">
                        + Tambah Kategori
                    </a>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Kategori</th>
                                @if(auth()->user()->role == 'admin')
                                    <th width="150">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kategori as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama_kategori }}</td>

                                    @if(auth()->user()->role == 'admin')
                                    <td>
                                        <a href="{{ route('kategori.edit', $item->idkategori) }}" 
                                           class="btn btn-sm btn-warning">
                                            Edit
                                        </a>

                                        <form action="{{ route('kategori.destroy', $item->idkategori) }}" 
                                              method="POST" 
                                              style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Yakin hapus kategori?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                    @endif

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection