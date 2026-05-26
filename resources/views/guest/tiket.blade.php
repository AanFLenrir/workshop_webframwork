@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center mt-5">
    <div class="card shadow" style="width: 400px;">
        <div class="card-body text-center p-5">
            <h4 class="card-title mb-4">Tiket Antrian</h4>
            <div style="border: 3px dashed #4B49AC; border-radius: 12px; padding: 30px;">
                <p class="text-muted mb-1">Nomor Antrian</p>
                <div style="font-size: 90px; font-weight: bold; color: #4B49AC; line-height: 1;">
                    {{ $antrian->nomor }}
                </div>
                <hr>
                <h5 class="mt-3">{{ $antrian->poli->nama }}</h5>
                <p class="text-muted">{{ $antrian->nama }}</p>
                <span class="badge bg-warning text-dark">{{ strtoupper($antrian->status) }}</span>
            </div>
            <a href="/guest" class="btn btn-primary mt-4 w-100">Daftar Lagi</a>
        </div>
    </div>
</div>
@endsection
