@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('home', ['category' => $product->category_id]) }}">{{ $product->category->name ?? 'Semua' }}</a></li>
            <li class="breadcrumb-item active">{{ Str::limit($product->name, 30) }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Image -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-body text-center" style="min-height: 400px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-image fa-8x text-secondary"></i>
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-md-7">
            <h2>{{ $product->name }}</h2>
            <div class="mb-3">
                <span class="badge bg-primary">{{ $product->category->icon ?? '' }} {{ $product->category->name ?? 'Uncategorized' }}</span>
            </div>
            
            <!-- Rating -->
            <div class="mb-3">
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
                <span class="ms-2">{{ number_format($avgRating, 1) }}</span>
                <span class="text-muted">({{ $product->total_reviews }} ulasan)</span>
            </div>

            <h3 class="text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</h3>
            
            <div class="mb-3">
                @if($product->stock > 0)
                    <span class="text-success"><i class="fas fa-check-circle"></i> Stok: {{ $product->stock }}</span>
                @else
                    <span class="text-danger"><i class="fas fa-times-circle"></i> Stok Habis</span>
                @endif
            </div>

            <p class="text-muted">{{ $product->description }}</p>

            @auth
                @if($product->stock > 0)
                    <button class="btn btn-primary btn-lg w-100 add-to-cart" data-product-id="{{ $product->id }}">
                        <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                    </button>
                @else
                    <button class="btn btn-secondary btn-lg w-100" disabled>
                        <i class="fas fa-times"></i> Stok Habis
                    </button>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-secondary btn-lg w-100">
                    <i class="fas fa-sign-in-alt"></i> Login untuk Beli
                </a>
            @endauth
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-5">
        <h4>Produk Terkait</h4>
        <div class="row">
            @foreach($relatedProducts as $related)
            <div class="col-md-3">
                <div class="card product-card">
                    <a href="{{ route('products.show', $related) }}" class="text-decoration-none text-dark">
                        <div class="card-body text-center">
                            <i class="fas fa-image fa-3x text-secondary"></i>
                            <h6 class="mt-2">{{ Str::limit($related->name, 20) }}</h6>
                            <p class="text-primary fw-bold">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Reviews Section -->
    <div class="mt-5">
        <h4>⭐ Ulasan Produk ({{ $product->total_reviews }})</h4>
        
        @auth
            @php
                $userReview = $product->reviews->where('user_id', auth()->id())->first();
                $hasPurchased = App\Models\Order::where('user_id', auth()->id())
                                ->whereHas('items', function($q) use ($product) {
                                    $q->where('product_id', $product->id);
                                })->exists();
            @endphp

            @if($userReview)
                <div class="alert alert-info">
                    <i class="fas fa-check-circle"></i> Anda sudah memberikan review untuk produk ini.
                </div>
            @elseif($hasPurchased)
                <div class="card mb-4">
                    <div class="card-body">
                        <h6>Tulis Ulasan</h6>
                        <form action="{{ route('review.store', $product) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Rating</label>
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="star-label">
                                            <input type="radio" name="rating" value="{{ $i }}" required>
                                            <i class="fas fa-star"></i>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            <div class="mb-3">
                                <textarea name="comment" class="form-control" rows="3" placeholder="Tulis komentar Anda..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle"></i> Beli produk ini terlebih dahulu untuk memberikan ulasan.
                </div>
            @endif
        @endauth

        <!-- List Reviews -->
        @forelse($product->reviews as $review)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <strong>{{ $review->user->name }}</strong>
                        <div class="mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-warning"></i>
                                @endif
                            @endfor
                        </div>
                    </div>
                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                </div>
                <p class="mb-0">{{ $review->comment ?? 'Tidak ada komentar' }}</p>
            </div>
        </div>
        @empty
        <p class="text-muted">Belum ada ulasan untuk produk ini.</p>
        @endforelse
    </div>
</div>

@auth
@push('scripts')
<script>
$(document).ready(function() {
    $('.add-to-cart').click(function() {
        let productId = $(this).data('product-id');
        
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
                alert('Terjadi kesalahan. Sil