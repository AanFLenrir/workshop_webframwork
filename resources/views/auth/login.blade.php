@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <h4>Hello! let's get started</h4>
    <h6 class="font-weight-light">Sign in to continue.</h6>

    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif

    <form class="pt-3" method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="form-group">
            <input type="email"
                name="email"
                class="form-control form-control-lg @error('email') is-invalid @enderror"
                placeholder="Email"
                value="{{ old('email') }}"
                required>

            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="form-group">
            <input type="password"
                name="password"
                class="form-control form-control-lg @error('password') is-invalid @enderror"
                placeholder="Password"
                required>

            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Button Login --}}
        <div class="mt-3">
            <button type="submit"
                class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                SIGN IN
            </button>
        </div>

        {{-- Divider --}}
        <div class="text-center mt-4 mb-3">
            <span class="text-muted">OR</span>
        </div>

        {{-- Login with Google --}}
        <div class="mt-2">
            <a href="{{ route('google.login') }}"
               class="btn btn-block btn-danger btn-lg font-weight-medium">
                Login with Google
            </a>
        </div>
    </form>
@endsection