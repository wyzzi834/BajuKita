@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1">Operator Pemesanan</h1>
                <p class="text-muted mb-0">Kelola invoice dan status pemesanan user.</p>
            </div>
            <a href="{{ route('logout.get') }}" class="btn btn-outline-danger align-self-start">Logout</a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted mb-1">Pending</p>
                        <h4 class="mb-0">{{ number_format($pendingCount, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted mb-1">Success</p>
                        <h4 class="mb-0">{{ number_format($successCount, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted mb-1">Failed</p>
                        <h4 class="mb-0">{{ number_format($failedCount, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Orderan User</h4>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('operator.order.index') }}" method="GET" class="mb-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Cari Invoice</label>
                            <div class="input-group">
                                <span class="input-group-text">#</span>
                                <input type="text" class="form-control" name="invoice" value="{{ request('invoice') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Cari Kata Kunci</label>
                            <input type="text" class="form-control" name="cari" value="{{ request('cari') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status Order</label>
                            <select class="form-control" name="status">
                                <option value="">-- Pilih --</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="{{ route('operator.order.index') }}" class="btn btn-danger">Reload</a>
                        </div>
                    </div>
                </form>

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
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>#INV-{{ $order->id }}</td>
                                    <td>{{ $order->user->name ?? '-' }}</td>
                                    <td>{{ $order->product->name ?? '-' }}</td>
                                    <td>Rp. {{ number_format($order->price, 0, ',', '.') }}</td>
                                    <td><span class="badge {{ $statusClass }}">{{ strtoupper($order->status) }}</span></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <form action="{{ route('operator.order.status', $order->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="input-group">
                                                    <select name="status" class="form-select">
                                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="success" {{ $order->status == 'success' ? 'selected' : '' }}>Success</option>
                                                        <option value="failed" {{ $order->status == 'failed' ? 'selected' : '' }}>Failed</option>
                                                        <option value="expired" {{ $order->status == 'expired' ? 'selected' : '' }}>Expired</option>
                                                    </select>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                            <form action="{{ route('operator.order.destroy', $order->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus invoice #INV-{{ $order->id }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn icon btn-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
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
@endsection
