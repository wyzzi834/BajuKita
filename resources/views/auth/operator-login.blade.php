@extends('layouts.auth')

@section('title')
    Login Operator BajuKita
@endsection

@section('content')
    <div class="col-md-4 col-11">
        <div class="card auth-card p-4 shadow-sm">
            <img src="{{ asset('assets/logo-bajukita.svg') }}" width="86" class="mx-auto mt-4" alt="BajuKita">
            <h3 class="text-center mt-4">Login Operator</h3>
            <p class="fw-bold text-center">Kelola pemesanan user BajuKita</p>
            @if (session('galat'))
                <div class="alert alert-danger">{{ session('galat') }}</div>
            @endif
            <form action="{{ route('operator.login.store') }}" method="POST">
                @csrf
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="email" class="form-control form-control-xl @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email', config('operator.email')) }}" placeholder="Email Operator">
                    <div class="form-control-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" class="form-control form-control-xl @error('password') is-invalid @enderror"
                        name="password" placeholder="Password">
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-check form-check-lg d-flex align-items-end">
                    <input class="form-check-input me-2" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label text-gray-600" for="remember">
                        Keep me logged in
                    </label>
                </div>
                <button class="btn btn-primary btn-block btn-lg shadow-sm mt-3">
                    Log in Operator
                </button>
            </form>
            <div class="text-center mt-3">
                <a href="{{ route('login') }}">Login sebagai admin/user</a>
            </div>
        </div>
    </div>
@endsection
