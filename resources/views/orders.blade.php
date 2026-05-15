@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Pemesanan</h4>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($orders->isNotEmpty())
                    <div class="alert alert-info d-flex flex-column flex-md-row justify-content-between gap-2">
                        <span>
                            Untuk konfirmasi pembayaran atau pesanan, hubungi {{ $operator['name'] }}
                            di {{ $operator['phone'] }}.
                        </span>
                        <a href="https://wa.me/{{ $operator['whatsapp'] }}" target="_blank" class="btn btn-success btn-sm">
                            WhatsApp Operator
                        </a>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-lg">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>INVOICE</th>
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
                                    <td>{{ $order->product->name ?? '-' }}</td>
                                    <td>Rp. {{ number_format($order->price, 0, ',', '.') }}</td>
                                    <td><span class="badge {{ $statusClass }}">{{ strtoupper($order->status) }}</span></td>
                                    <td>
                                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus pemesanan #INV-{{ $order->id }}?')">
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
                                    <td colspan="6" class="text-center">Belum ada pemesanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
