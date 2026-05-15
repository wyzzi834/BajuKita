@extends('layouts.backend.master')

@section('title')
    My Account
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>My Account</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar avatar-xl">
                            <img src="{{ asset('assets/images/faces/1.jpg') }}" alt="Avatar">
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                            <p class="text-muted mb-0">{{ ucfirst(auth()->user()->role ?? 'user') }}</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-lg">
                            <tbody>
                                <tr>
                                    <th>Nama</th>
                                    <td>{{ auth()->user()->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ auth()->user()->email }}</td>
                                </tr>
                                <tr>
                                    <th>Role</th>
                                    <td>{{ ucfirst(auth()->user()->role ?? 'user') }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Daftar</th>
                                    <td>{{ auth()->user()->created_at?->format('d M Y') ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
