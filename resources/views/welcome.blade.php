<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BajuKita</title>
    <link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main/app-dark.css') }}">
    <style>
        body {
            background: #f5f7fb;
            color: #17223b;
        }

        .store-header {
            position: sticky;
            top: 0;
            z-index: 20;
            background: rgba(255, 255, 255, .94);
            border-bottom: 1px solid #e5e7eb;
            backdrop-filter: blur(10px);
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            color: #24356f;
            text-decoration: none;
        }

        .brand-logo {
            width: 40px;
            height: 40px;
        }

        .hero {
            background: #111827;
            color: #fff;
            padding: 46px 0;
        }

        .hero h1 {
            max-width: 720px;
            font-weight: 800;
        }

        .product-card {
            height: 100%;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #fff;
            color: #17223b;
        }

        .product-card img {
            width: 100%;
            aspect-ratio: 4 / 3;
            object-fit: cover;
            background: #eef2f7;
        }

        .product-body {
            padding: 16px;
        }

        .product-title {
            min-height: 48px;
            color: #17223b;
        }

        .section-title,
        .product-price {
            color: #17223b;
        }

        .muted-copy {
            color: #6c757d;
        }

        .category-pill {
            border: 1px solid #e5e7eb;
            background: #f8f9fa;
            color: #17223b;
        }

        .product-category {
            background: #e8eef6;
            color: #26384f;
        }

        .product-status {
            background: #dff5eb;
            color: #0f6846;
        }
    </style>
</head>

<body>
    <header class="store-header">
        <nav class="container d-flex align-items-center justify-content-between py-3">
            <a class="brand h4 mb-0" href="{{ route('storefront') }}">
                <img src="{{ asset('assets/logo-bajukita.svg') }}" class="brand-logo" alt="BajuKita">
                <span>BajuKita</span>
            </a>
            <div class="d-flex gap-2">
                @auth
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin') }}" class="btn btn-outline-primary">Dashboard</a>
                    @endif
                    <a href="{{ route('logout.get') }}" class="btn btn-primary">Logout</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                @endauth
            </div>
        </nav>
    </header>

    <section class="hero">
        <div class="container">
            <h1 class="display-6 mb-3">BajuKita, katalog baju pilihan untuk aktivitas harian.</h1>
            <p class="lead mb-0 text-white-50">Cek koleksi terbaru, pilih produk yang cocok, lalu masuk saat ingin melakukan pemesanan.</p>
        </div>
    </section>

    <main class="container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
            <div>
                <h2 class="h4 mb-1 section-title">Produk Tersedia</h2>
                <p class="muted-copy mb-0">Semua produk yang tampil saat ini tersedia untuk dibeli.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($categories as $category)
                    <span class="badge category-pill">{{ $category->name }}</span>
                @endforeach
            </div>
        </div>

        <div class="row g-4">
            @forelse ($products as $product)
                <div class="col-sm-6 col-lg-3">
                    <article class="product-card">
                        <img src="{{ url('storage/image/' . $product->image) }}" alt="{{ $product->name }}">
                        <div class="product-body">
                            <div class="d-flex justify-content-between gap-2 mb-2">
                                <span class="badge product-category">{{ $product->category->name ?? '-' }}</span>
                                <span class="badge product-status">Tersedia</span>
                            </div>
                            <h3 class="h6 product-title">{{ $product->name }}</h3>
                            <p class="fw-bold mb-3 product-price">Rp. {{ number_format($product->price, 0, ',', '.') }}</p>
                            @auth
                                <form action="{{ route('checkout.store', $product) }}" method="POST"
                                    onsubmit="return confirm('Checkout produk {{ $product->name }}?')">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100">Checkout</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary w-100">Login untuk Checkout</a>
                            @endauth
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
    </main>
</body>

</html>
