@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="page-header">
  <h3 class="page-title">Dashboard</h3>
</div>

<div class="row">

  {{-- TOTAL BUKU --}}
  <div class="col-md-4 stretch-card grid-margin">
    <div class="card bg-gradient-danger card-img-holder text-white">
      <div class="card-body">
        <h4 class="font-weight-normal mb-3">
          Total Buku
          <i class="mdi mdi-book-open-page-variant mdi-24px float-right"></i>
        </h4>
        <h2 class="mb-5">{{ $totalBuku }}</h2>
        <h6 class="card-text">Data buku tersedia</h6>
      </div>
    </div>
  </div>

  {{-- TOTAL KATEGORI --}}
  <div class="col-md-4 stretch-card grid-margin">
    <div class="card bg-gradient-info card-img-holder text-white">
      <div class="card-body">
        <h4 class="font-weight-normal mb-3">
          Total Kategori
          <i class="mdi mdi-shape mdi-24px float-right"></i>
        </h4>
        <h2 class="mb-5">{{ $totalKategori }}</h2>
        <h6 class="card-text">Kategori terdaftar</h6>
      </div>
    </div>
  </div>

  {{-- TOTAL USER --}}
  <div class="col-md-4 stretch-card grid-margin">
    <div class="card bg-gradient-success card-img-holder text-white">
      <div class="card-body">
        <h4 class="font-weight-normal mb-3">
          User Login
          <i class="mdi mdi-account mdi-24px float-right"></i>
        </h4>
        <h2 class="mb-5">{{ $totalUser }}</h2>
        <h6 class="card-text">Total pengguna</h6>
      </div>
    </div>
  </div>

</div>

@endsection