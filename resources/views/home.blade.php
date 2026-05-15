@extends('layouts.app')

@section('content')
    <style>
        .store-hero {
            background: #111827;
            color: #fff;
            border-radius: 8px;
            padding: 28px;
        }

        .product-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            height: 100%;
            overflow: hidden;
            background: #fff;
        }

        .product-card img {
            width: 100%;
            aspect-ratio: 4 / 3;
            object-fit: cover;
            background: #f3f4f6;
        }

        .product-body {
            padding: 16px;
        }

        .product-title {
            min-height: 48px;
        }
    </style>

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success d-flex flex-column flex-md-row justify-content-between gap-2">
                <span>{{ session('success') }}</span>
                <a href="https://wa.me/{{ $operator['whatsapp'] }}" target="_blank" class="btn btn-success btn-sm">
                    Hubungi {{ $operator['name'] }}
                </a>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <section class="store-hero mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                <div>
                    <h1 class="h3 mb-2">Katalog Produk</h1>
                    <p class="mb-0 text-white-50">Pilih produk yang tersedia dan checkout langsung dari halaman ini.</p>
                </div>
                <form action="{{ route('home') }}" method="GET" class="d-flex gap-2 align-items-end">
                    <div>
                        <label for="kategori" class="form-label mb-1">Kategori</label>
                        <select name="kategori" id="kategori" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('kategori') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('home') }}" class="btn btn-outline-light">Reset</a>
                </form>
            </div>
        </section>

        <div class="row g-4">
            @forelse ($products as $product)
                <div class="col-sm-6 col-lg-3">
                    <article class="product-card">
                        <img src="{{ url('storage/image/' . $product->image) }}" alt="{{ $product->name }}">
                        <div class="product-body">
                            <div class="d-flex justify-content-between gap-2 mb-2">
                                <span class="badge text-bg-secondary">{{ $product->category->name ?? '-' }}</span>
                                <span class="badge text-bg-success">Tersedia</span>
                            </div>
                            <h2 class="h6 product-title">{{ $product->name }}</h2>
                            <p class="fw-bold mb-3">Rp. {{ number_format($product->price, 0, ',', '.') }}</p>
                            <form action="{{ route('checkout.store', $product) }}" method="POST"
                                onsubmit="return confirm('Checkout produk {{ $product->name }}?')">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100">Checkout</button>
                            </form>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning">Produk belum tersedia.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
@endsection
