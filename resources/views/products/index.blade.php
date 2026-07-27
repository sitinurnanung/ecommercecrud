@extends('layouts.app')

@section('title', 'Home - E-Commerce')

@section('content')
<div class="hero-section">
    <div class="container text-center">
        <h1 class="display-4 fw-bold">Selamat Datang di E-Commerce</h1>
        <p class="lead">Temukan produk berkualitas dengan harga terbaik</p>
        
        <!-- Search Bar -->
        <form action="{{ route('home') }}" method="GET" class="mt-4">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control form-control-lg" 
                               placeholder="Cari produk..." value="{{ request('search') }}">
                        <button class="btn btn-light btn-lg" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="container">
    <div class="row">
        <!-- Sidebar Kategori -->
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-list"></i> Kategori</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('home') }}" class="list-group-item list-group-item-action 
                            {{ !request('category') ? 'active' : '' }}">
                            <i class="fas fa-th-large"></i> Semua Produk
                        </a>
                        @foreach($categories as $category)
                        <a href="{{ route('home', ['category' => $category->id]) }}" 
                           class="list-group-item list-group-item-action 
                           {{ request('category') == $category->id ? 'active' : '' }}">
                            {{ $category->icon }} {{ $category->name }}
                            <span class="badge bg-secondary float-end">
                                {{ $category->products->count() }}
                            </span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Produk -->
        <div class="col-md-9">
            <!-- Sorting -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <span class="text-muted">Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} 
                    dari {{ $products->total() }} produk</span>
                </div>
                <div>
                    <form action="{{ route('home') }}" method="GET" class="d-inline">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="row">
                @foreach($products as $product)
                <div class="col-md-4 col-lg-4 mb-4">
                    <div class="card product-card h-100">
                        <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark">
                            <div class="card-img-top text-center bg-light" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-image fa-5x text-secondary"></i>
                            </div>
                            <div class="card-body">
                                <small class="text-muted">{{ $product->category->icon ?? '' }} {{ $product->category->name ?? 'Uncategorized' }}</small>
                                <h5 class="card-title mt-1">{{ Str::limit($product->name, 30) }}</h5>
                                
                                <!-- Rating Stars -->
                                <div class="mb-2">
                                    @php
                                        $avgRating = $product->average_rating;
                                        $fullStars = floor($avgRating);
                                        $halfStar = $avgRating - $fullStars >= 0.5 ? 1 : 0;
                                        $emptyStars = 5 - $fullStars - $halfStar;
                                    @endphp
                                    @for($i = 1; $i <= $fullStars; $i++)
                                        <i class="fas fa-star text-warning"></i>
                                    @endfor
                                    @if($halfStar)
                                        <i class="fas fa-star-half-alt text-warning"></i>
                                    @endif
                                    @for($i = 1; $i <= $emptyStars; $i++)
                                        <i class="far fa-star text-warning"></i>
                                    @endfor
                                    <span class="text-muted small">({{ $product->total_reviews }})</span>
                                </div>
                                
                                <p class="card-text fw-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                @if($product->stock > 0)
                                    <small class="text-success"><i class="fas fa-check-circle"></i> Tersedia</small>
                                @else
                                    <small class="text-danger"><i class="fas fa-times-circle"></i> Habis</small>
                                @endif
                            </div>
                        </a>
                        <div class="card-footer bg-white border-0">
                            @auth
                            <button class="btn btn-primary w-100 add-to-cart" data-product-id="{{ $product->id }}">
                                <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                            </button>
                            @else
                            <a href="{{ route('login') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-sign-in-alt"></i> Login untuk Beli
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@auth
@push('scripts')
<script>
$(document).ready(function() {
    $('.add-to-cart').click(function() {
        let productId = $(this).data('product-id');
        let button = $(this);
        
        $.ajax({
            url: '/cart/add/' + productId,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    $('#cartCount').text(response.cartCount);
                    alert('Produk berhasil ditambahkan ke keranjang!');
                }
            },
            error: function() {
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        });
    });
});
</script>
@endpush
@endauth
@endsection