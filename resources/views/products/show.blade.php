@extends('layouts.app')

@section('title', $product->name . ' - TechCatalog')

@section('content')
<div class="max-w-5xl mx-auto px-3 sm:px-6 lg:px-8 py-6 sm:py-8">

    <nav class="flex items-center text-[11px] sm:text-xs text-zinc-400 mb-4 sm:mb-6 gap-2 font-mono">
        <a href="{{ route('home') }}" class="hover:text-zinc-900 transition">Storefront</a>
        <span>/</span>
        <a href="{{ route('home', ['category' => $product->category->slug ?? '']) }}" class="hover:text-zinc-900 transition truncate max-w-[100px]">
            {{ $product->category->name ?? 'Semua' }}
        </a>
        <span>/</span>
        <span class="text-zinc-800 font-semibold truncate">{{ Str::limit($product->name, 20) }}</span>
    </nav>

    <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs overflow-hidden p-4 sm:p-6 lg:p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-10 items-start">

            <div class="bg-zinc-100/80 rounded-xl overflow-hidden aspect-square border border-zinc-200/60 relative">
                <img src="{{ $product->image_url }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-full object-cover">
            </div>

            <div class="space-y-4 sm:space-y-5">

                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] sm:text-[11px] font-semibold bg-zinc-100 text-zinc-700">
                        {{ $product->category->name ?? 'Gear' }}
                    </span>
                    @if($product->stock > 0)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] sm:text-[11px] font-medium bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Stok {{ $product->stock }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] sm:text-[11px] font-medium bg-rose-50 text-rose-700 ring-1 ring-rose-600/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            Stok Habis
                        </span>
                    @endif
                </div>

                <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-zinc-900 tracking-tight leading-snug">
                    {{ $product->name }}
                </h1>

                <div class="p-3.5 sm:p-4 bg-zinc-50 rounded-xl border border-zinc-200/60">
                    <span class="text-[10px] text-zinc-400 font-semibold uppercase tracking-wider block">Harga</span>
                    <span class="text-xl sm:text-2xl font-mono font-bold text-zinc-900">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                </div>

                <div>
                    <h3 class="text-xs font-semibold text-zinc-900 mb-1.5">Deskripsi Produk</h3>
                    <div class="text-xs sm:text-sm text-zinc-600 leading-relaxed whitespace-pre-line">
                        {{ $product->description }}
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="pt-4 border-t border-zinc-100">
                    @auth
                        @if($product->stock > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                <!-- Tombol Keranjang -->
                                <form action="{{ route('cart.add', $product->slug) }}" method="POST" class="w-full">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-full bg-zinc-100 hover:bg-zinc-200 active:scale-[0.98] text-zinc-900 font-semibold py-3 px-4 rounded-xl transition-all text-xs sm:text-sm flex items-center justify-center gap-2 cursor-pointer shadow-2xs">
                                        <svg class="w-4 h-4 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        <span>+ Keranjang</span>
                                    </button>
                                </form>

                                <!-- Tombol Beli Sekarang -->
                                <button type="button" onclick="openBuyNow()" 
                                        class="w-full bg-zinc-900 hover:bg-zinc-800 active:scale-[0.98] text-white font-semibold py-3 px-5 rounded-xl transition-all shadow-2xs text-xs sm:text-sm flex items-center justify-center gap-2 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    <span>Beli Sekarang</span>
                                </button>
                            </div>
                        @else
                            <button disabled class="w-full bg-zinc-100 text-zinc-400 font-semibold py-3 px-5 rounded-xl cursor-not-allowed text-xs sm:text-sm">
                                Stok Tidak Tersedia
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="block w-full bg-zinc-900 hover:bg-zinc-800 text-white font-semibold py-3 px-5 rounded-xl transition-all text-center text-xs sm:text-sm">
                            Login untuk Membeli
                        </a>
                    @endauth

                    <a href="{{ route('home') }}" class="block w-full mt-2 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 font-semibold py-2.5 px-4 rounded-xl transition-all text-center text-xs sm:text-sm">
                        ← Kembali ke Katalog
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL BELI SEKARANG --}}
{{-- ============================================================ --}}
@auth
@if($product->stock > 0)
<div id="buyNowModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-zinc-900/60 backdrop-blur-sm" onclick="closeBuyNow()"></div>

    <div class="flex min-h-full items-center justify-center p-3 sm:p-4">
        <div class="relative w-full max-w-lg bg-zinc-50 rounded-2xl shadow-2xl overflow-hidden z-10 my-6">

            <!-- Header -->
            <div class="bg-white px-4 sm:px-5 py-3.5 border-b border-zinc-100 flex items-center justify-between sticky top-0 z-10">
                <div class="flex items-center gap-3">
                    <button type="button" onclick="closeBuyNow()" class="p-1.5 rounded-lg hover:bg-zinc-100 transition-colors">
                        <svg class="w-5 h-5 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </button>
                    <h2 class="text-sm sm:text-base font-bold text-zinc-900">Konfirmasi Pesanan</h2>
                </div>
            </div>

            <form action="{{ route('orders.direct', $product->slug) }}" method="POST" id="buyNowForm">
                @csrf

                <div class="p-3 sm:p-4 space-y-3 max-h-[70vh] overflow-y-auto">

                    <!-- Produk -->
                    <div class="bg-white rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-16 h-16 rounded-lg bg-zinc-100 overflow-hidden shrink-0 border border-zinc-200">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] font-bold text-zinc-400 uppercase">{{ $product->category->name ?? 'Gear' }}</p>
                                <p class="text-xs font-bold text-zinc-900 truncate">{{ $product->name }}</p>
                                <p class="text-[10px] text-zinc-500 mt-0.5">Stok: <span class="text-emerald-600 font-bold">{{ $product->stock }}</span></p>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-sm font-bold font-mono text-zinc-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>

                                    <!-- Quantity -->
                                    <div class="flex items-center gap-1 bg-zinc-100 rounded-lg p-0.5">
                                        <button type="button" onclick="decrementQty()" class="w-6 h-6 flex items-center justify-center rounded text-zinc-700 hover:bg-white font-bold text-sm">−</button>
                                        <input type="number" name="quantity" id="orderQty" value="1" min="1" max="{{ $product->stock }}" oninput="updateTotalCalc()" class="w-8 text-center font-mono font-bold text-xs bg-transparent border-0 focus:ring-0 p-0">
                                        <button type="button" onclick="incrementQty()" class="w-6 h-6 flex items-center justify-center rounded text-zinc-700 hover:bg-white font-bold text-sm">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Pengiriman -->
                    <div class="bg-white rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-1 h-4 bg-zinc-900 rounded-full"></div>
                            <span class="text-xs font-bold text-zinc-900 uppercase tracking-wider">Data Pengiriman</span>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <label class="block text-[10px] font-semibold text-zinc-700 mb-1">Nama Penerima <span class="text-rose-500">*</span></label>
                                <input type="text" name="customer_name" required value="{{ auth()->user()->name }}" placeholder="Nama penerima" class="w-full text-xs px-3 py-2 rounded-lg border border-zinc-200 focus:outline-none focus:border-zinc-900 bg-zinc-50">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold text-zinc-700 mb-1">No. WhatsApp <span class="text-rose-500">*</span></label>
                                <input type="tel" name="customer_phone" required placeholder="0812xxxxxxxx" class="w-full text-xs px-3 py-2 rounded-lg border border-zinc-200 focus:outline-none focus:border-zinc-900 bg-zinc-50">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold text-zinc-700 mb-1">Alamat Lengkap <span class="text-rose-500">*</span></label>
                                <textarea name="shipping_address" rows="2" required placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Kode Pos" class="w-full text-xs px-3 py-2 rounded-lg border border-zinc-200 focus:outline-none focus:border-zinc-900 bg-zinc-50 resize-none"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Pembayaran -->
                    <div class="bg-white rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-1 h-4 bg-zinc-900 rounded-full"></div>
                            <span class="text-xs font-bold text-zinc-900 uppercase tracking-wider">Metode Pembayaran</span>
                        </div>

                        <div class="grid grid-cols-3 gap-2">
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="cod" checked class="peer sr-only">
                                <div class="p-2.5 rounded-lg border-2 border-zinc-200 bg-white text-center peer-checked:border-zinc-900 peer-checked:bg-zinc-50 transition-all">
                                    <div class="text-lg">💵</div>
                                    <p class="text-[10px] font-bold text-zinc-900">COD</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="transfer" class="peer sr-only">
                                <div class="p-2.5 rounded-lg border-2 border-zinc-200 bg-white text-center peer-checked:border-zinc-900 peer-checked:bg-zinc-50 transition-all">
                                    <div class="text-lg">🏦</div>
                                    <p class="text-[10px] font-bold text-zinc-900">Transfer</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="qris" class="peer sr-only">
                                <div class="p-2.5 rounded-lg border-2 border-zinc-200 bg-white text-center peer-checked:border-zinc-900 peer-checked:bg-zinc-50 transition-all">
                                    <div class="text-lg">📱</div>
                                    <p class="text-[10px] font-bold text-zinc-900">QRIS</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="bg-white rounded-xl p-4">
                        <label class="block text-[10px] font-semibold text-zinc-700 mb-1">Catatan (Opsional)</label>
                        <input type="text" name="notes" placeholder="Contoh: Warna hitam, packing rapi" class="w-full text-xs px-3 py-2 rounded-lg border border-zinc-200 focus:outline-none focus:border-zinc-900 bg-zinc-50">
                    </div>

                    <!-- Ringkasan -->
                    <div class="bg-white rounded-xl p-4">
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between text-zinc-600">
                                <span>Harga Satuan</span>
                                <span class="font-mono">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-zinc-600">
                                <span>Jumlah</span>
                                <span class="font-mono" id="summaryQty">1 unit</span>
                            </div>
                            <div class="flex justify-between text-emerald-600">
                                <span>Ongkir</span>
                                <span class="font-semibold">Gratis</span>
                            </div>
                            <div class="pt-2 mt-2 border-t border-zinc-100 flex justify-between items-center">
                                <span class="text-xs font-bold text-zinc-900 uppercase">Total</span>
                                <span class="text-base font-bold font-mono text-zinc-900" id="summaryTotal">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Footer Tombol -->
                <div class="bg-white border-t border-zinc-100 p-3 sm:p-4 sticky bottom-0">
                    <div class="flex gap-2">
                        <button type="button" onclick="closeBuyNow()" class="flex-1 py-2.5 px-4 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-xs font-semibold transition-colors">
                            Batal
                        </button>
                        <button type="submit" id="btnBuyNow" class="flex-1 py-2.5 px-4 rounded-xl bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-semibold transition-all flex items-center justify-center gap-1.5">
                            <span>Buat Pesanan</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endauth

<script>
    const unitPrice = {{ (int) $product->price }};
    const maxStock = {{ (int) $product->stock }};

    function formatRupiah(num) {
        return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function openBuyNow() {
        document.getElementById('buyNowModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeBuyNow() {
        document.getElementById('buyNowModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function updateTotalCalc() {
        const input = document.getElementById('orderQty');
        let qty = parseInt(input.value) || 1;
        if (qty < 1) qty = 1;
        if (qty > maxStock) qty = maxStock;
        input.value = qty;

        const total = unitPrice * qty;
        document.getElementById('summaryTotal').innerText = formatRupiah(total);
        document.getElementById('summaryQty').innerText = qty + ' unit';
    }

    function incrementQty() {
        const input = document.getElementById('orderQty');
        let val = parseInt(input.value) || 1;
        if (val < maxStock) {
            input.value = val + 1;
            updateTotalCalc();
        }
    }

    function decrementQty() {
        const input = document.getElementById('orderQty');
        let val = parseInt(input.value) || 1;
        if (val > 1) {
            input.value = val - 1;
            updateTotalCalc();
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeBuyNow();
    });

    document.getElementById('buyNowForm')?.addEventListener('submit', function() {
        const btn = document.getElementById('btnBuyNow');
        btn.disabled = true;
        btn.innerHTML = '<span>Memproses...</span>';
    });
</script>
@endsection