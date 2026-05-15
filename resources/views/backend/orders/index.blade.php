@extends('layouts.backend.master')
@section('title')
    Orderan Masuk
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="d-flex justify-content-start">
                        <div class="stats-icon green mb-2">
                            <i class="bi bi-check-square-fill"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-muted font-semibold">Orderan Pending</h6>
                            <h6 class="font-extrabold mb-0">{{ number_format($pendingCount, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="d-flex justify-content-start">
                        <div class="stats-icon red mb-2">
                            <i class="bi bi-info-square-fill"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-muted font-semibold">Orderan Expired</h6>
                            <h6 class="font-extrabold mb-0">{{ number_format($expiredCount, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>@yield('title')</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="" method="GET">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-3">
                                <label for="invoice" class="form-label">Cari Invoice</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">#</span>
                                    <input type="text" class="form-control" autocomplete="off" name="invoice"
                                        value="{{ request('invoice') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="cari" class="form-label">Cari Kata Kunci</label>
                                <input type="text" name="cari" class="form-control" autocomplete="off" id="cari"
                                    value="{{ request('cari') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="basicSelect" class="form-label">Status Order</label>
                                <select class="form-control" autocomplete="off" id="basicSelect" name="status">
                                    <option value="">-- Pilih --</option>
                                    <option {{ request('status') == 'pending' ? 'selected' : '' }} value="pending">Pending</option>
                                    <option {{ request('status') == 'success' ? 'selected' : '' }} value="success">Success</option>
                                    <option {{ request('status') == 'failed' ? 'selected' : '' }} value="failed">Failed</option>
                                    <option {{ request('status') == 'expired' ? 'selected' : '' }} value="expired">Expired</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search"></i> Search
                                    </button>
                                    <a href="{{ route('admin.order.index') }}" class="btn btn-danger">
                                        <i class="bi bi-arrow-clockwise"></i> Reload
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Table with outer spacing -->
                    <div class="table-responsive">
                        <table class="table table-lg">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>INVOICE</th>
                                    <th>USER</th>
                                    <th>PRODUK</th>
                                    <th>HARGA</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    @php
                                        $statusClass = [
                                            'pending' => 'bg-primary',
                                            'success' => 'bg-success',
                                            'failed' => 'bg-danger',
                                            'expired' => 'bg-warning',
                                        ][$order->status] ?? 'bg-secondary';

                                        $price = is_numeric($order->price)
                                            ? 'Rp. ' . number_format($order->price, 0, ',', '.')
                                            : $order->price;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">#INV-{{ $order->id }}</td>
                                        <td>{{ $order->user->name ?? '-' }}</td>
                                        <td class="text-bold-500">{{ $order->product->name ?? '-' }}</td>
                                        <td>{{ $price }}</td>
                                        <td>
                                            <span class="badge {{ $statusClass }}">{{ strtoupper($order->status) }}</span>
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.order.destroy', $order->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus orderan #INV-{{ $order->id }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn icon btn-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada orderan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
