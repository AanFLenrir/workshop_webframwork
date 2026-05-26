@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">👥 Data Customer</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('customer.create1') }}" class="btn btn-primary btn-sm">
                <i class="mdi mdi-camera me-1"></i> Tambah Customer 1 (Blob)
            </a>
            <a href="{{ route('customer.create2') }}" class="btn btn-success btn-sm">
                <i class="mdi mdi-camera-plus me-1"></i> Tambah Customer 2 (File)
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>No. Telepon</th>
                            <th>Jenis Foto</th>
                            <th>Tanggal Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $i => $customer)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                @if($customer->photo_blob)
                                    <img src="{{ $customer->photo_blob }}"
                                         alt="foto" class="rounded-circle"
                                         style="width:50px;height:50px;object-fit:cover;">
                                @elseif($customer->photo_path)
                                    <img src="{{ asset('storage/' . $customer->photo_path) }}"
                                         alt="foto" class="rounded-circle"
                                         style="width:50px;height:50px;object-fit:cover;">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                                         style="width:50px;height:50px;">
                                        <i class="mdi mdi-account text-white fs-4"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->phone ?? '-' }}</td>
                            <td>
                                @if($customer->photo_blob)
                                    <span class="badge bg-primary">Blob</span>
                                @elseif($customer->photo_path)
                                    <span class="badge bg-success">File</span>
                                @else
                                    <span class="badge bg-secondary">Guest</span>
                                @endif
                            </td>
                            <td>{{ $customer->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada data customer
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
