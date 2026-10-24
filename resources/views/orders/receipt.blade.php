@extends('layouts.app')

@section('title', 'Nota Pesanan #' . $order->order_number . ' - TechCatalog')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Notifikasi Sukses -->
    <div class="mb-6 p-4 sm:p-5 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-900 shadow-2xs flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-bold tracking-tight">Pesanan Berhasil Dibuat!</h2>
                <p class="text-xs text-emerald-700 mt-0.5">Terima kasih atas pesanan Anda. Kami segera menyiapkan barang pesanan Anda.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-3.5 py-2 rounded-xl bg-white border border-emerald-300 text-emerald-900 text-xs font-semibold hover:bg-emerald-100/50 transition-all flex items-center gap-1.5 shadow-2xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Struk
            </button>
        </div>
    </div>

    <!-- Lembar Nota / Invoice Resmi -->
    <div id="invoice-sheet" class="bg-white rounded-3xl border border-zinc-200/90 shadow-sm overflow-hidden p-6 sm:p-10">
        
        <!-- Header Invoice -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-zinc-200/80">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-zinc-900 inline-block"></span>
                    <span class="font-bold text-lg text-zinc-900 tracking-tight">TechCatalog</span>
                    <span class="text-[10px] font-mono uppercase px-2 py-0.5 rounded-md bg-zinc-100 text-zinc-600 font-semibold">Official Receipt</span>
                </div>
                <p class="text-xs text-zinc-400 mt-1">Sistem Katalog & Transaksi PKL</p>
            </div>
            <div class="text-left sm:text-right">
                <span class="text-[10px] text-zinc-400 font-mono font-semibold uppercase tracking-wider block">Nomor Invoice</span>
                <span class="text-base sm:text-lg font-mono font-bold text-zinc-900">{{ $order->order_number }}</span>
                <span class="text-xs text-zinc-500 block mt-0.5">{{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
            </div>
        </div>

        <!-- Badges Status -->
        <div class="py-4 flex flex-wrap items-center gap-2 border-b border-zinc-100">
            <span class="text-xs font-semibold text-zinc-500">Status Pesanan:</span>
            @if($order->status === 'completed')
                <span class="px-2.5 py-0.5 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Selesai</span>
            @elseif($order->status === 'processing')
                <span class="px-2.5 py-0.5 rounded-md text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200">Sedang Diproses</span>
            @else
                <span class="px-2.5 py-0.5 rounded-md text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">Menunggu Konfirmasi</span>
            @endif

            <span class="text-xs font-semibold text-zinc-500 ml-2">Pembayaran:</span>
            @if($order->payment_status === 'paid')
                <span class="px-2.5 py-0.5 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Lunas</span>
            @else
                <span class="px-2.5 py-0.5 rounded-md text-xs font-semibold bg-zinc-100 text-zinc-700 border border-zinc-200">Belum Lunas</span>
            @endif
        </div>

        <!-- 2 Kolom Info: Pembeli & Metode Pembayaran -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 py-6 border-b border-zinc-200/80 text-xs">
            <!-- Kolom Pembeli -->
            <div class="p-4 bg-zinc-50 rounded-2xl border border-zinc-200/60 space-y-2">
                <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider block">Data Penerima / Pembeli</span>
                <div class="space-y-1 text-zinc-700">
                    <p><strong class="text-zinc-900 text-sm block">{{ $order->customer_name }}</strong></p>
                    <p class="flex items-center gap-1.5 text-zinc-600">
                        <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span>{{ $order->customer_phone }}</span>
                    </p>
                    <p class="text-zinc-600 mt-2 leading-relaxed">
                        <strong class="text-zinc-800">Alamat Kirim:</strong><br>
                        {{ $order->shipping_address_only }}
                    </p>
                    @if($order->notes)
                        <p class="text-zinc-500 pt-1 italic">Catatan: {{ $order->notes }}</p>
                    @endif
                </div>
            </div>

            <!-- Kolom Pembayaran -->
            <div class="p-4 bg-zinc-50 rounded-2xl border border-zinc-200/60 space-y-2">
                <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider block">Instruksi Pembayaran</span>
                <div class="space-y-1.5 text-zinc-700">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-zinc-900">{{ $order->payment_method_label }}</span>
                    </div>

                    @if($order->payment_method === 'cod')
                        <div class="p-2.5 rounded-xl bg-emerald-50/80 border border-emerald-200 text-emerald-800 text-[11px] leading-relaxed">
                            📦 <strong>Bayar di Tempat (COD):</strong> Siapkan uang pas sebesar <strong class="font-mono text-zinc-900">{{ $order->formatted_total_price }}</strong> saat kurir mengantarkan paket ke alamat Anda.
                        </div>
                    @elseif($order->payment_method === 'transfer')
                        <div class="p-2.5 rounded-xl bg-blue-50/80 border border-blue-200 text-blue-900 text-[11px] leading-relaxed space-y-1">
                            <p>Silakan transfer ke salah satu rekening resmi berikut:</p>
                            <p class="font-mono font-semibold text-zinc-900">🏦 BCA: 8801-2345-6789</p>
                            <p class="font-mono font-semibold text-zinc-900">🏦 Mandiri: 1370-0192-8374</p>
                            <p class="text-[10px] text-zinc-500">a.n <strong>TechCatalog Store</strong></p>
                        </div>
                    @elseif($order->payment_method === 'qris')
                        <div class="p-2.5 rounded-xl bg-purple-50/80 border border-purple-200 text-purple-900 text-[11px] leading-relaxed">
                            📱 <strong>Scan QRIS:</strong> Silakan scan QRIS TechCatalog melalui aplikasi perbankan atau e-wallet (GoPay, OVO, Dana, ShopeePay).
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabel Rincian Produk -->
        <div class="py-6 border-b border-zinc-200/80">
            <h3 class="text-xs font-bold text-zinc-900 uppercase tracking-wider mb-4">Rincian Barang yang Dipesan</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-zinc-200 text-zinc-400 uppercase text-[10px] font-semibold font-mono">
                            <th class="py-2.5">Produk</th>
                            <th class="py-2.5 text-center">Harga Satuan</th>
                            <th class="py-2.5 text-center">Jumlah</th>
                            <th class="py-2.5 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @foreach($order->items as $item)
                        <tr>
                            <td class="py-3 pr-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-zinc-100 overflow-hidden border border-zinc-200/60 shrink-0">
                                        <img src="{{ $item->product->image_url ?? asset('images/products/default.jpg') }}" alt="{{ $item->product->name ?? 'Produk' }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="font-semibold text-zinc-900">{{ $item->product->name ?? 'Produk Tidak Ditemukan' }}</p>
                                        <p class="text-[11px] text-zinc-500 font-mono mt-0.5">Item ID: #{{ $item->product_id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 text-center font-mono text-zinc-700">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </td>
                            <td class="py-3 text-center font-mono font-semibold text-zinc-900">
                                {{ $item->quantity }}x
                            </td>
                            <td class="py-3 text-right font-mono font-bold text-zinc-900">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Total Tagihan -->
        <div class="pt-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="text-xs text-zinc-500">
                <p>Simpan nomor invoice ini untuk melacak status pesanan Anda.</p>
            </div>
            <div class="w-full sm:w-72 space-y-2 text-xs">
                <div class="flex justify-between text-zinc-600">
                    <span>Subtotal Produk</span>
                    <span class="font-mono">{{ $order->formatted_total_price }}</span>
                </div>
                <div class="flex justify-between text-zinc-600">
                    <span>Biaya Ongkos Kirim</span>
                    <span class="font-mono text-emerald-600 font-semibold">Gratis (Rp 0)</span>
                </div>
                <div class="pt-2 border-t border-zinc-200 flex justify-between items-center text-sm font-bold text-zinc-900">
                    <span>Total Pembayaran</span>
                    <span class="font-mono text-lg text-zinc-900">{{ $order->formatted_total_price }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Tombol Aksi Bawah -->
    <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
        <a href="{{ route('home') }}" class="w-full sm:w-auto px-5 py-3 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-800 font-semibold transition-all text-center">
            &larr; Lanjut Belanja Produk Lain
        </a>

        <div class="w-full sm:w-auto flex flex-col sm:flex-row gap-2.5">
            <!-- Tombol Konfirmasi WhatsApp Otomatis -->
            @php
                $waMessage = "Halo TechCatalog, saya ingin konfirmasi pesanan saya:\n"
                           . "• No Invoice: {$order->order_number}\n"
                           . "• Nama: {$order->customer_name}\n"
                           . "• Total: {$order->formatted_total_price}\n"
                           . "• Metode: {$order->payment_method_label}\n"
                           . "Mohon segera diproses ya, terima kasih!";
                $waUrl = "https://api.whatsapp.com/send?phone=6281234567890&text=" . urlencode($waMessage);
            @endphp
            <a href="{{ $waUrl }}" target="_blank" class="w-full sm:w-auto px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white font-semibold transition-all flex items-center justify-center gap-2 shadow-2xs">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.025 3.312l-.542 2.378 2.454-.537c.995.551 1.848.813 2.831.813 3.179 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.766-5.768-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.802 0-3.486-.484-4.945-1.328l-5.055 1.106 1.129-4.954c-.958-1.523-1.529-3.328-1.529-5.267 0-5.514 4.486-10 10-10s10 4.486 10 10z"/></svg>
                Konfirmasi via WhatsApp
            </a>

            <button onclick="window.print()" class="w-full sm:w-auto px-5 py-3 rounded-xl bg-zinc-900 hover:bg-zinc-800 active:scale-[0.98] text-white font-semibold transition-all flex items-center justify-center gap-2 shadow-2xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Struk
            </button>
        </div>
    </div>

</div>

<!-- CSS Khusus Mode Print / Cetak -->
<style>
@media print {
    body {
        background: white !important;
        color: black !important;
    }
    header, footer, nav, .mb-6.p-4, .mt-6.flex {
        display: none !important;
    }
    #invoice-sheet {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
    }
}
</style>
@endsection
