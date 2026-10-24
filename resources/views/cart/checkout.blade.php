@extends('layouts.app')

@section('title', 'Checkout Pesanan - TechCatalog')

@section('content')
<div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8 py-6 sm:py-8">

    <div class="mb-5 sm:mb-8">
        <h1 class="text-xl sm:text-2xl font-bold text-zinc-900 tracking-tight flex items-center gap-2">
            <span>Konfirmasi Pesanan</span>
        </h1>
        <p class="text-[11px] sm:text-xs text-zinc-500 mt-1">Lengkapi data pengiriman & pembayaran.</p>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6 items-start">

            <div class="lg:col-span-7 space-y-4 sm:space-y-6">
                <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs p-4 sm:p-6 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-zinc-100">
                        <span class="w-2 h-2 rounded-full bg-zinc-900 inline-block"></span>
                        <h2 class="text-xs font-bold text-zinc-900 uppercase tracking-wider">Data Pengiriman</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">Nama Penerima <span class="text-rose-500">*</span></label>
                            <input type="text" name="customer_name" required
                                   value="{{ old('customer_name', $user->name ?? '') }}"
                                   placeholder="Nama lengkap"
                                   class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-200 focus:outline-none focus:border-zinc-900 focus:ring-1 focus:ring-zinc-900 transition-all bg-zinc-50">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">No. WhatsApp <span class="text-rose-500">*</span></label>
                            <input type="tel" name="customer_phone" required
                                   value="{{ old('customer_phone') }}"
                                   placeholder="0812xxxxxxxx"
                                   class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-200 focus:outline-none focus:border-zinc-900 focus:ring-1 focus:ring-zinc-900 transition-all bg-zinc-50">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 mb-1">Alamat Lengkap <span class="text-rose-500">*</span></label>
                        <textarea name="shipping_address" required rows="3"
                                  placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Kode Pos"
                                  class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-200 focus:outline-none focus:border-zinc-900 focus:ring-1 focus:ring-zinc-900 transition-all bg-zinc-50 resize-none">{{ old('shipping_address') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-zinc-600 mb-1">Catatan (Opsional)</label>
                        <input type="text" name="notes" 
                               placeholder="Contoh: Titip ke security"
                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-200 focus:outline-none focus:border-zinc-900 transition-all bg-zinc-50">
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs p-4 sm:p-6 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-zinc-100">
                        <span class="w-2 h-2 rounded-full bg-zinc-900 inline-block"></span>
                        <h2 class="text-xs font-bold text-zinc-900 uppercase tracking-wider">Metode Pembayaran</h2>
                    </div>

                    <div class="grid grid-cols-3 gap-2 sm:gap-3 text-xs">
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="cod" checked class="peer sr-only">
                            <div class="border-2 border-zinc-200 rounded-xl p-3 flex flex-col items-center justify-center text-center peer-checked:border-zinc-900 peer-checked:bg-zinc-50 peer-checked:ring-1 peer-checked:ring-zinc-900 transition-all hover:border-zinc-300">
                                <span class="text-xl sm:text-2xl mb-1"></span>
                                <span class="font-bold text-zinc-900 text-[10px] sm:text-xs">COD</span>
                                <span class="text-[9px] sm:text-[10px] text-zinc-500 mt-0.5">Bayar di Tempat</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="transfer" class="peer sr-only">
                            <div class="border-2 border-zinc-200 rounded-xl p-3 flex flex-col items-center justify-center text-center peer-checked:border-zinc-900 peer-checked:bg-zinc-50 peer-checked:ring-1 peer-checked:ring-zinc-900 transition-all hover:border-zinc-300">
                                <span class="font-bold text-zinc-900 text-[10px] sm:text-xs">Transfer</span>
                                <span class="text-[9px] sm:text-[10px] text-zinc-500 mt-0.5">BCA/Mandiri</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="qris" class="peer sr-only">
                            <div class="border-2 border-zinc-200 rounded-xl p-3 flex flex-col items-center justify-center text-center peer-checked:border-zinc-900 peer-checked:bg-zinc-50 peer-checked:ring-1 peer-checked:ring-zinc-900 transition-all hover:border-zinc-300">
                                <span class="text-xl sm:text-2xl mb-1"></span>
                                <span class="font-bold text-zinc-900 text-[10px] sm:text-xs">QRIS</span>
                                <span class="text-[9px] sm:text-[10px] text-zinc-500 mt-0.5">E-Wallet</span>
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-5 lg:sticky lg:top-20">
                <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs p-4 sm:p-6 space-y-4">

                    <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
                        <h2 class="text-xs font-bold text-zinc-900 uppercase tracking-wider">Rincian Pesanan</h2>
                        <a href="{{ route('cart.index') }}" class="text-[11px] font-semibold text-zinc-500 hover:text-zinc-900 hover:underline">
                            Edit
                        </a>
                    </div>

                    <div class="divide-y divide-zinc-100 max-h-64 overflow-y-auto -mx-1 px-1">
                        @foreach($cartItems as $item)
                            @php $sub = ($item->product->price ?? 0) * $item->quantity; @endphp
                            <div class="py-2.5 flex items-center justify-between gap-3 text-xs">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-10 h-10 rounded-lg bg-zinc-100 border border-zinc-200/60 overflow-hidden shrink-0">
                                        <img src="{{ $item->product->image_url ?? asset('images/products/default.jpg') }}" 
                                             alt="{{ $item->product->name ?? '' }}" 
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div class="truncate">
                                        <p class="font-semibold text-zinc-900 truncate text-[11px]">{{ $item->product->name ?? 'Produk' }}</p>
                                        <p class="text-[10px] text-zinc-400 font-mono">{{ $item->quantity }}x</p>
                                    </div>
                                </div>
                                <span class="font-mono font-bold text-zinc-900 shrink-0 text-[11px]">
                                    Rp {{ number_format($sub, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-3 border-t border-zinc-100 space-y-2 text-xs">
                        <div class="flex justify-between text-zinc-600">
                            <span>Subtotal</span>
                            <span class="font-mono">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-zinc-600">
                            <span>Ongkir</span>
                            <span class="font-mono text-emerald-600 font-semibold">Gratis</span>
                        </div>
                        <div class="pt-3 border-t border-zinc-100 flex justify-between items-center">
                            <span class="text-xs font-bold text-zinc-900 uppercase tracking-wider">Total</span>
                            <span class="font-mono text-base font-bold text-zinc-900">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <button type="submit" id="btnProcessCheckout" 
                            class="w-full py-3.5 px-4 rounded-xl bg-zinc-900 hover:bg-zinc-800 active:scale-[0.98] text-white text-xs font-semibold transition-all flex items-center justify-center gap-2 shadow-2xs cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Buat Pesanan</span>
                    </button>

                    <div class="p-3 bg-zinc-50 rounded-xl border border-zinc-100 text-[10px] text-zinc-500 leading-relaxed">
                        Transaksi aman & terenkripsi.
                    </div>

                </div>
            </div>

        </div>
    </form>
</div>

<script>
    document.getElementById('checkoutForm')?.addEventListener('submit', function() {
        const btn = document.getElementById('btnProcessCheckout');
        btn.disabled = true;
        btn.innerHTML = '<span>Memproses...</span>';
    });
</script>
@endsection