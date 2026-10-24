@extends('layouts.app')

@section('title', 'Admin - Manajemen Pesanan Masuk')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 tracking-tight">Manajemen Pesanan Masuk</h1>
            <p class="text-sm text-zinc-500 mt-1">Kelola transaksi, verifikasi pembayaran, dan pantau pengiriman produk.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.products.index') }}" class="px-3.5 py-2 rounded-xl bg-white border border-zinc-200 text-zinc-700 text-xs font-semibold hover:bg-zinc-50 transition-all shadow-2xs">
                Kelola Produk
            </a>
            <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-xl bg-zinc-900 text-white text-xs font-semibold hover:bg-zinc-800 transition-all shadow-2xs">
                Lihat Storefront
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="p-5 bg-white rounded-2xl border border-zinc-200/80 shadow-2xs">
            <span class="text-xs font-medium text-zinc-400 uppercase tracking-wider block">Total Pesanan</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold font-mono text-zinc-900">{{ $stats['total'] }}</span>
                <span class="text-[11px] text-zinc-400">Semua Waktu</span>
            </div>
        </div>

        <div class="p-5 bg-white rounded-2xl border border-zinc-200/80 shadow-2xs">
            <span class="text-xs font-medium text-amber-600 uppercase tracking-wider block">Menunggu Konfirmasi</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold font-mono text-amber-600">{{ $stats['pending'] }}</span>
                <span class="text-[11px] text-amber-500">Perlu Tindakan</span>
            </div>
        </div>

        <div class="p-5 bg-white rounded-2xl border border-zinc-200/80 shadow-2xs">
            <span class="text-xs font-medium text-sky-600 uppercase tracking-wider block">Sedang Diproses</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold font-mono text-sky-600">{{ $stats['processing'] }}</span>
                <span class="text-[11px] text-sky-500">Sedang Disiapkan</span>
            </div>
        </div>

        <div class="p-5 bg-white rounded-2xl border border-zinc-200/80 shadow-2xs">
            <span class="text-xs font-medium text-emerald-600 uppercase tracking-wider block">Total Omset Lunas</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-xl font-bold font-mono text-emerald-700">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-1 text-xs font-semibold">
        <a href="{{ route('admin.orders.index') }}" 
           class="px-3.5 py-1.5 rounded-lg transition-all {{ empty($status) ? 'bg-zinc-900 text-white' : 'bg-white border border-zinc-200 text-zinc-600 hover:bg-zinc-50' }}">
            Semua Pesanan ({{ $stats['total'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" 
           class="px-3.5 py-1.5 rounded-lg transition-all {{ $status === 'pending' ? 'bg-amber-600 text-white' : 'bg-white border border-zinc-200 text-zinc-600 hover:bg-zinc-50' }}">
            Menunggu ({{ $stats['pending'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" 
           class="px-3.5 py-1.5 rounded-lg transition-all {{ $status === 'processing' ? 'bg-sky-600 text-white' : 'bg-white border border-zinc-200 text-zinc-600 hover:bg-zinc-50' }}">
            Diproses ({{ $stats['processing'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" 
           class="px-3.5 py-1.5 rounded-lg transition-all {{ $status === 'completed' ? 'bg-emerald-600 text-white' : 'bg-white border border-zinc-200 text-zinc-600 hover:bg-zinc-50' }}">
            Selesai ({{ $stats['completed'] }})
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-zinc-50/80 border-b border-zinc-200/80 text-zinc-500 font-semibold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="px-6 py-4">No. Invoice & Tanggal</th>
                        <th class="px-6 py-4">Pelanggan / Penerima</th>
                        <th class="px-6 py-4">Produk Dipesan</th>
                        <th class="px-6 py-4">Total & Metode</th>
                        <th class="px-6 py-4">Status Bayar</th>
                        <th class="px-6 py-4">Status Pesanan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 text-zinc-700">
                    @forelse($orders as $order)
                    <tr class="hover:bg-zinc-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div>
                                <a href="{{ route('orders.receipt', $order->order_number) }}" target="_blank" class="font-mono font-bold text-zinc-900 hover:underline">
                                    {{ $order->order_number }}
                                </a>
                                <p class="text-[11px] text-zinc-400 mt-0.5">{{ $order->created_at->format('d/m/Y H:i') }} WIB</p>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div>
                                <p class="font-semibold text-zinc-900">{{ $order->customer_name }}</p>
                                <p class="text-[11px] text-zinc-500 font-mono">{{ $order->customer_phone }}</p>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="space-y-1 max-w-xs truncate">
                                @foreach($order->items as $item)
                                    <p class="truncate font-medium text-zinc-800">
                                        <span class="font-mono font-bold">{{ $item->quantity }}x</span> {{ $item->product->name ?? 'Produk' }}
                                    </p>
                                @endforeach
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div>
                                <p class="font-mono font-bold text-zinc-900">{{ $order->formatted_total_price }}</p>
                                <span class="text-[10px] font-semibold text-zinc-500 uppercase">{{ $order->payment_method_label }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="payment_status" value="{{ $order->payment_status === 'paid' ? 'pending' : 'paid' }}">
                                <button type="submit" 
                                        title="Klik untuk ubah status pembayaran"
                                        class="cursor-pointer inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold transition-all {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20 hover:bg-emerald-100' : 'bg-amber-50 text-amber-700 ring-1 ring-amber-600/20 hover:bg-amber-100' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $order->payment_status === 'paid' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                    <span>{{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum Lunas' }}</span>
                                </button>
                            </form>
                        </td>

                        <td class="px-6 py-4">
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <select name="status" 
                                        onchange="this.form.submit()" 
                                        class="text-xs font-semibold rounded-lg border border-zinc-200 bg-white py-1 px-2.5 text-zinc-800 focus:outline-none focus:border-zinc-900 cursor-pointer">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Diproses</option>
                                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Batal</option>
                                </select>
                            </form>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('orders.receipt', $order->order_number) }}" 
                                   target="_blank"
                                   title="Lihat Struk / Invoice"
                                   class="p-2 rounded-lg bg-zinc-100 text-zinc-600 hover:text-zinc-900 hover:bg-zinc-200 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-sm text-zinc-500">
                            Belum ada pesanan masuk. Pesanan yang dibuat oleh pembeli akan muncul di sini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="p-4 border-t border-zinc-100">
            {{ $orders->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
