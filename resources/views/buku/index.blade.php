@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Data Buku</h4>

                {{-- Alert Success --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Tombol Tambah (Admin Only) --}}
                @if(auth()->user()->role == 'admin')
                    <a href="{{ route('buku.create') }}" class="btn btn-primary mb-3">
                        + Tambah Buku
                    </a>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Pengarang</th>
                                <th>Kategori</th>
                                @if(auth()->user()->role == 'admin')
                                    <th width="150">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($buku as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->kode }}</td>
                                    <td>{{ $item->judul }}</td>
                                    <td>{{ $item->pengarang }}</td>
                                    <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>

                                    @if(auth()->user()->role == 'admin')
                                    <td>
                                        <a href="{{ route('buku.edit', $item->idbuku) }}" 
                                           class="btn btn-sm btn-warning">Edit</a>

                                        <form action="{{ route('buku.destroy', $item->idbuku) }}" 
                                              method="POST" 
                                              style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Yakin hapus data?')">
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