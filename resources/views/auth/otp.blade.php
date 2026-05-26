@extends('layouts.auth')

@section('title', 'Verifikasi OTP')

@section('content')
    <h4>Verifikasi OTP</h4>
    <h6 class="font-weight-light">Masukkan kode OTP yang dikirim ke email Anda.</h6>

    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif

    <form class="pt-3" method="POST" action="{{ route('otp.verify') }}">
        @csrf

        <div class="form-group">
            <input type="text"
                   name="otp"
                   class="form-control form-control-lg text-center"
                   placeholder="Masukkan 6 digit OTP"
                   maxlength="6"
                   required>
        </div>

        <div class="mt-3">
            <button type="submit"
                class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                Verifikasi
            </button>
        </div>
    </form>
@endsection