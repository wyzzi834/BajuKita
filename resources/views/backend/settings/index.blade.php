@extends('layouts.backend.master')

@section('title')
    Settings
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>Settings</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Aplikasi</label>
                        <input type="text" class="form-control" value="{{ config('app.name') }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Akun</label>
                        <input type="email" class="form-control" value="{{ auth()->user()->email }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-control" value="{{ ucfirst(auth()->user()->role ?? 'user') }}" disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
