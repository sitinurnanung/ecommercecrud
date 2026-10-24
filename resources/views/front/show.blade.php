@extends('layouts.app')

@section('title', $product->name . ' - TechCatalog')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <nav class="flex items-center text-xs text-zinc-400 mb-6 gap-2 font-mono">
        <a href="{{ route('home') }}" class="hover:text-zinc-900 transition">Catalog</a>
        <span>/</span>
        <span class="text-zinc-800 font-semibold truncate">{{ $product->name }}</span>
    </nav>

    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium flex items-center gap-2">
            <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs overflow-hidden p-6 sm:p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-10 items-start">
            
            <div class="bg-zinc-100/80 rounded-xl overflow-hidden aspect-square border border-zinc-200/60">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            </div>

            <div class="space-y-5">
                <div>
                    @if($product->stock > 0)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-medium bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Stok Tersedia ({{ $product->stock }} Unit)
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-medium bg-rose-50 text-rose-700 ring-1 ring-rose-600/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            Stok Habis
                        </span>
                    @endif
                </div>

                <h1 class="text-xl sm:text-2xl font-bold text-zinc-900 tracking-tight leading-snug">
                    {{ $product->name }}
                </h1>

                <div class="p-3.5 bg-zinc-50 rounded-xl border border-zinc-200/60">
                    <span class="text-[10px] text-zinc-400 font-semibold uppercase tracking-wider block">Harga Resmi</span>
                    <span class="text-2xl font-mono font-bold text-zinc-900">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                </div>

                <div>
                    <h3 class="text-xs font-semibold text-zinc-900 mb-1.5">Deskripsi Produk:</h3>
                    <div class="text-xs text-zinc-600 leading-relaxed whitespace-pre-line">
                        {{ $product->description }}
                    </div>
                </div>

                <div class="pt-4 flex flex-col sm:flex-row gap-2.5 border-t border-zinc-100">
                    @if($product->stock > 0)
                        <form action="{{ route('cart.add', $product->slug) }}" method="POST" class="w-full sm:w-auto flex-1">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-full bg-zinc-100 hover:bg-zinc-200 active:scale-[0.98] text-zinc-900 font-semibold py-3 px-4 rounded-xl transition-all text-xs flex items-center justify-center gap-2 cursor-pointer shadow-2xs">
                                <svg class="w-4 h-4 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <span>+ Keranjang</span>
                            </button>
                        </form>

                        <button type="button" onclick="openCheckoutModal()" class="w-full sm:w-auto flex-1 bg-zinc-900 hover:bg-zinc-800 active:scale-[0.98] text-white font-semibold py-3 px-5 rounded-xl transition-all shadow-2xs text-xs flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <span>Beli Sekarang</span>
                        </button>
                    @else
                        <button disabled class="w-full sm:w-auto flex-1 bg-zinc-100 text-zinc-400 font-semibold py-3 px-5 rounded-xl cursor-not-allowed text-xs">
                            Stok Tidak Tersedia
                        </button>
                    @endif

                    <a href="{{ route('home') }}" class="w-full sm:w-auto bg-zinc-100 hover:bg-zinc-200 text-zinc-700 font-semibold py-3 px-4 rounded-xl transition-all text-center text-xs">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="checkoutModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div id="modalBackdrop" onclick="closeCheckoutModal()" class="fixed inset-0 bg-zinc-900/60 backdrop-blur-sm"></div>

    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-lg bg-zinc-50 rounded-2xl shadow-2xl overflow-hidden z-10 my-8">
            
            <div class="bg-white px-5 py-4 border-b border-zinc-100 flex items-center justify-between sticky top-0 z-10">
                <div class="flex items-center gap-3">
                    <button type="button" onclick="closeCheckoutModal()" class="p-1.5 rounded-lg hover:bg-zinc-100 transition-colors">
                        <svg class="w-5 h-5 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </button>
                    <h2 class="text-base font-bold text-zinc-900">Konfirmasi Pesanan</h2>
                </div>
            </div>

            <form action="{{ route('orders.direct', $product->slug) }}" method="POST" id="checkoutForm">
                @csrf

                <div class="p-4 space-y-3 max-h-[70vh] overflow-y-auto">
                    <div class="bg-white rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-zinc-900 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-bold text-zinc-900">Alamat Pengiriman</span>
                                </div>

                                <div class="mb-2">
                                    <input type="text" 
                                           name="customer_name" 
                                           required 
                                           value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                                           placeholder="Nama penerima"
                                           class="w-full text-sm font-semibold text-zinc-900 border-0 border-b border-zinc-200 focus:border-zinc-900 focus:ring-0 px-0 py-1 bg-transparent placeholder-zinc-400">
                                </div>

                                <div class="mb-2">
                                    <input type="tel" 
                                           name="customer_phone" 
                                           required 
                                           value="{{ old('customer_phone') }}"
                                           placeholder="0812xxxxxxxx"
                                           class="w-full text-xs text-zinc-600 border-0 border-b border-zinc-200 focus:border-zinc-900 focus:ring-0 px-0 py-1 bg-transparent placeholder-zinc-400 font-mono">
                                </div>

                                <div>
                                    <textarea name="shipping_address" 
                                              required 
                                              rows="2" 
                                              placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten, Kode Pos"
                                              class="w-full text-xs text-zinc-600 border-0 border-b border-zinc-200 focus:border-zinc-900 focus:ring-0 px-0 py-1 bg-transparent placeholder-zinc-400 resize-none">{{ old('shipping_address') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-4">
                        <!-- Toko -->
                        <div class="flex items-center gap-2 mb-3 pb-3 border-b border-zinc-100">
                            <span class="w-5 h-5 rounded bg-zinc-900 flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </span>
                            <span class="text-xs font-bold text-zinc-900">TechCatalog Official</span>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-zinc-900 text-white font-bold">Mall</span>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-16 h-16 rounded-lg bg-zinc-100 overflow-hidden shrink-0 border border-zinc-200">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-zinc-900 line-clamp-2">{{ $product->name }}</p>
                                <p class="text-[10px] text-zinc-400 mt-0.5">Stok: {{ $product->stock }}</p>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-sm font-bold font-mono text-zinc-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    
                                    <div class="flex items-center gap-1 bg-zinc-100 rounded-lg p-0.5">
                                        <button type="button" onclick="decrementQty()" class="w-6 h-6 flex items-center justify-center rounded text-zinc-700 hover:bg-white font-bold text-sm">−</button>
                                        <input type="number" name="quantity" id="orderQty" value="1" min="1" max="{{ $product->stock }}" oninput="updateTotalCalc()" class="w-8 text-center font-mono font-bold text-xs bg-transparent border-0 focus:ring-0 p-0">
                                        <button type="button" onclick="incrementQty()" class="w-6 h-6 flex items-center justify-center rounded text-zinc-700 hover:bg-white font-bold text-sm">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 pt-3 border-t border-zinc-100">
                            <input type="text" name="notes" placeholder="Catatan untuk penjual (opsional)" class="w-full text-xs text-zinc-600 placeholder-zinc-400 border-0 focus:ring-0 px-0 bg-transparent">
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-4 h-4 text-zinc-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                            <span class="text-xs font-bold text-zinc-900">Pilihan Pengiriman</span>
                        </div>
                        <div class="space-y-1.5">
                            <label class="flex items-center justify-between p-2.5 rounded-lg border-2 border-zinc-200 hover:border-zinc-300 cursor-pointer transition-all has-checked:border-zinc-900 has-checked:bg-zinc-50">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="shipping_method" value="reguler" checked class="text-zinc-900 focus:ring-zinc-900">
                                    <div>
                                        <p class="text-xs font-semibold text-zinc-900">Reguler (JNE)</p>
                                        <p class="text-[10px] text-zinc-500">Estimasi 2-4 hari</p>
                                    </div>
                                </div>
                                <span class="text-xs font-mono font-bold text-zinc-900">Rp 20.000</span>
                            </label>
                            <label class="flex items-center justify-between p-2.5 rounded-lg border-2 border-zinc-200 hover:border-zinc-300 cursor-pointer transition-all has-checked:border-zinc-900 has-checked:bg-zinc-50">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="shipping_method" value="instan" class="text-zinc-900 focus:ring-zinc-900">
                                    <div>
                                        <p class="text-xs font-semibold text-zinc-900">Instan (GoSend)</p>
                                        <p class="text-[10px] text-zinc-500">Sampai hari ini</p>
                                    </div>
                                </div>
                                <span class="text-xs font-mono font-bold text-zinc-900">Rp 35.000</span>
                            </label>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-4 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-zinc-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                            <span class="text-xs font-bold text-zinc-900">Voucher Toko</span>
                        </div>
                        <button type="button" class="text-xs font-semibold text-zinc-900 hover:underline">Pilih Voucher</button>
                    </div>

                    <div class="bg-white rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-4 h-4 text-zinc-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            <span class="text-xs font-bold text-zinc-900">Metode Pembayaran</span>
                        </div>
                        <div class="space-y-1.5">
                            <label class="flex items-center gap-3 p-2.5 rounded-lg border border-zinc-200 hover:bg-zinc-50 cursor-pointer transition-all has-checked:border-zinc-900 has-checked:bg-zinc-50">
                                <input type="radio" name="payment_method" value="transfer" checked class="text-zinc-900 focus:ring-zinc-900">
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-zinc-900">Transfer Bank</p>
                                    <p class="text-[10px] text-zinc-500">BCA, Mandiri, BNI, BRI</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-2.5 rounded-lg border border-zinc-200 hover:bg-zinc-50 cursor-pointer transition-all has-checked:border-zinc-900 has-checked:bg-zinc-50">
                                <input type="radio" name="payment_method" value="cod" class="text-zinc-900 focus:ring-zinc-900">
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-zinc-900">COD (Bayar di Tempat)</p>
                                    <p class="text-[10px] text-zinc-500">Bayar saat barang tiba</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-2.5 rounded-lg border border-zinc-200 hover:bg-zinc-50 cursor-pointer transition-all has-checked:border-zinc-900 has-checked:bg-zinc-50">
                                <input type="radio" name="payment_method" value="qris" class="text-zinc-900 focus:ring-zinc-900">
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-zinc-900">QRIS</p>
                                    <p class="text-[10px] text-zinc-500">GoPay, OVO, Dana, ShopeePay</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-4 h-4 text-zinc-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="text-xs font-bold text-zinc-900">Rincian Pesanan</span>
                        </div>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between">
                                <span class="text-zinc-500">Subtotal Produk</span>
                                <span class="font-mono text-zinc-900" id="subtotalText">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-zinc-500">Ongkos Kirim</span>
                                <span class="font-mono text-zinc-900">Rp 20.000</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-zinc-500">Diskon Voucher</span>
                                <span class="font-mono text-zinc-900">-Rp 0</span>
                            </div>
                            <div class="pt-2 mt-2 border-t border-zinc-100 flex justify-between items-center">
                                <span class="text-sm font-bold text-zinc-900">Total Pesanan</span>
                                <span class="text-base font-bold font-mono text-zinc-900" id="totalCalcText">Rp {{ number_format($product->price + 20000, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- FOOTER: TOTAL & TOMBOL -->
                <div class="bg-white border-t border-zinc-100 p-4 sticky bottom-0">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs text-zinc-500">Total Pembayaran</span>
                        <span class="text-lg font-bold font-mono text-zinc-900" id="footerTotal">Rp {{ number_format($product->price + 20000, 0, ',', '.') }}</span>
                    </div>
                    <button type="submit" id="btnSubmitOrder" class="w-full py-3 bg-zinc-900 hover:bg-zinc-800 text-white font-bold text-sm rounded-xl shadow-sm active:scale-[0.98] transition-all">
                        Buat Pesanan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    const unitPrice = {{ (int) $product->price }};
    const maxStock = {{ (int) $product->stock }};
    const shippingCost = 20000;

    function formatRupiah(num) {
        return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function openCheckoutModal() {
        document.getElementById('checkoutModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        updateTotalCalc();
    }

    function closeCheckoutModal() {
        document.getElementById('checkoutModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function updateTotalCalc() {
        const input = document.getElementById('orderQty');
        let qty = parseInt(input.value) || 1;
        if (qty < 1) qty = 1;
        if (qty > maxStock) qty = maxStock;
        input.value = qty;

        const subtotal = unitPrice * qty;
        const total = subtotal + shippingCost;

        document.getElementById('subtotalText').innerText = formatRupiah(subtotal);
        document.getElementById('totalCalcText').innerText = formatRupiah(total);
        document.getElementById('footerTotal').innerText = formatRupiah(total);
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

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') closeCheckoutModal();
    });

    document.getElementById('checkoutForm')?.addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmitOrder');
        btn.disabled = true;
        btn.innerHTML = 'Memproses...';
    });
</script>
@endsection