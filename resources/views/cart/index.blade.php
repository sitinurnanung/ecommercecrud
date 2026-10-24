@extends('layouts.app')

@section('title', 'Keranjang Belanja - TechCatalog')

@section('content')
<div class="max-w-5xl mx-auto px-3 sm:px-6 lg:px-8 py-6 sm:py-8">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-zinc-900 tracking-tight">Keranjang Belanja</h1>
            <p class="text-[11px] sm:text-xs text-zinc-500 mt-0.5">Periksa kembali barang pilihan Anda.</p>
        </div>
        <a href="{{ route('home') }}" class="text-[11px] sm:text-xs font-semibold text-zinc-600 hover:text-zinc-900 transition-colors whitespace-nowrap">
            &larr; Lanjut Belanja
        </a>
    </div>

    @if($cartItems->isEmpty())
        <div class="bg-white rounded-2xl border border-zinc-200/80 p-10 sm:p-12 text-center max-w-lg mx-auto shadow-2xs space-y-4">
            <div>
                <h3 class="text-base font-bold text-zinc-900">Keranjang Belanja Masih Kosong</h3>
                <p class="text-xs text-zinc-500 mt-2 leading-relaxed">
                    Belum ada produk di keranjang. Yuk, kunjungi katalog dan temukan  favoritmu!
                </p>
            </div>
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-semibold transition-all shadow-2xs">
                Mulai Belanja
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 items-start">

            <div class="lg:col-span-8 space-y-2.5">

                <div class="bg-white rounded-xl border border-zinc-200/80 px-4 py-3 flex items-center justify-between shadow-2xs">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" id="selectAll" checked
                               class="w-4 h-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900/20 cursor-pointer">
                        <span class="text-xs font-semibold text-zinc-900">Pilih Semua</span>
                        <span class="text-[10px] text-zinc-400">({{ $cartItems->count() }} produk)</span>
                    </label>
                    <span class="text-[10px] text-zinc-400 font-mono hidden sm:inline">Total: {{ $cartItems->sum('quantity') }} item</span>
                </div>

                @foreach($cartItems as $item)
                    @php $itemSubtotal = ($item->product->price ?? 0) * $item->quantity; @endphp

                    <div class="bg-white rounded-xl border border-zinc-200/80 shadow-2xs p-3 sm:p-4 flex items-start gap-3">

                        <input type="checkbox" 
                               class="product-checkbox mt-1 w-4 h-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900/20 cursor-pointer shrink-0"
                               data-price="{{ $item->product->price ?? 0 }}"
                               data-qty="{{ $item->quantity }}"
                               checked>

                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl bg-zinc-100 border border-zinc-200/60 overflow-hidden shrink-0">
                            <img src="{{ $item->product->image_url ?? asset('images/products/default.jpg') }}" 
                                 alt="{{ $item->product->name ?? 'Produk' }}" 
                                 class="w-full h-full object-cover">
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xs sm:text-sm font-semibold text-zinc-900 line-clamp-2 leading-tight">
                                        <a href="{{ route('products.show', $item->product->slug ?? '#') }}" class="hover:underline">
                                            {{ $item->product->name ?? 'Produk Tidak Ditemukan' }}
                                        </a>
                                    </h3>
                                    <p class="text-[10px] text-zinc-400 mt-0.5">{{ $item->product->category->name ?? 'Gear' }}</p>
                                </div>

                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini dari keranjang?');" class="shrink-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-zinc-400 hover:text-rose-600 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>

                            <div class="mt-2 flex items-center justify-between gap-2 flex-wrap">
                                <span class="font-mono text-xs sm:text-sm font-bold text-zinc-900">
                                    Rp {{ number_format($item->product->price ?? 0, 0, ',', '.') }}
                                </span>

                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center bg-zinc-50 border border-zinc-200 rounded-lg overflow-hidden">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button" onclick="decrementCart(this)" class="w-7 h-7 flex items-center justify-center text-zinc-600 hover:bg-zinc-100 font-bold text-sm transition-colors">−</button>
                                    <input type="number" 
                                           name="quantity" 
                                           value="{{ $item->quantity }}" 
                                           min="1" 
                                           max="{{ $item->product->stock ?? 999 }}" 
                                           onchange="this.form.submit()"
                                           class="w-9 text-center text-xs font-mono font-bold text-zinc-900 border-0 bg-transparent focus:ring-0 p-0">
                                    <button type="button" onclick="incrementCart(this)" class="w-7 h-7 flex items-center justify-center text-zinc-600 hover:bg-zinc-100 font-bold text-sm transition-colors">+</button>
                                </form>
                            </div>
                        </div>

                    </div>
                @endforeach

            </div>

            <div class="lg:col-span-4 lg:sticky lg:top-20">
                <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs p-5 space-y-4">

                    <div class="space-y-2.5 text-xs">
                        <div class="flex justify-between text-zinc-600">
                            <span>Total Harga</span>
                            <span class="font-mono font-medium text-zinc-900" id="summarySubtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-zinc-600">
                            <span>Ongkos Kirim</span>
                            <span class="font-mono font-semibold text-emerald-600">Gratis</span>
                        </div>
                        <div class="pt-3 border-t border-zinc-100 flex justify-between items-center">
                            <span class="text-xs font-bold text-zinc-900 uppercase tracking-wider">Total</span>
                            <span class="font-mono text-base font-bold text-zinc-900" id="summaryTotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="w-full py-3 px-4 rounded-xl bg-zinc-900 hover:bg-zinc-800 active:scale-[0.98] text-white text-xs font-semibold transition-all text-center block shadow-2xs">
                        Checkout
                    </a>

                    <div class="p-3 bg-zinc-50 rounded-xl border border-zinc-100 text-[10px] text-zinc-500 leading-relaxed">
                        Transaksi aman & terenkripsi.
                    </div>
                </div>
            </div>

        </div>

        <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-zinc-200 shadow-[0_-4px_12px_rgba(0,0,0,0.05)] p-3 z-30">
            <div class="flex items-center justify-between gap-3 max-w-5xl mx-auto">
                <div>
                    <span class="text-[10px] text-zinc-400 block uppercase">Total</span>
                    <span class="font-mono text-sm font-bold text-zinc-900" id="mobileTotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('checkout.index') }}" class="px-5 py-2.5 rounded-xl bg-zinc-900 hover:bg-zinc-800 active:scale-95 text-white text-xs font-semibold transition-all">
                    Checkout
                </a>
            </div>
        </div>
        <div class="lg:hidden h-20"></div>

    @endif
</div>

<script>
    function incrementCart(btn) {
        const input = btn.parentElement.querySelector('input[name="quantity"]');
        const max = parseInt(input.max);
        let val = parseInt(input.value) || 1;
        if (val < max) {
            input.value = val + 1;
            input.form.requestSubmit();
        }
    }
    function decrementCart(btn) {
        const input = btn.parentElement.querySelector('input[name="quantity"]');
        let val = parseInt(input.value) || 1;
        if (val > 1) {
            input.value = val - 1;
            input.form.requestSubmit();
        }
    }

    document.getElementById('selectAll')?.addEventListener('change', function() {
        document.querySelectorAll('.product-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateSummary();
    });

    document.querySelectorAll('.product-checkbox').forEach(cb => {
        cb.addEventListener('change', updateSummary);
    });

    function updateSummary() {
        let total = 0;
        document.querySelectorAll('.product-checkbox:checked').forEach(cb => {
            const price = parseInt(cb.dataset.price) || 0;
            const qty = parseInt(cb.dataset.qty) || 0;
            total += price * qty;
        });
        const fmt = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('summarySubtotal').textContent = fmt;
        document.getElementById('summaryTotal').textContent = fmt;
        
        const mobileTotal = document.getElementById('mobileTotal');
        if (mobileTotal) mobileTotal.textContent = fmt;
    }
</script>
@endsection