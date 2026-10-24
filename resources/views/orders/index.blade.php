@extends('layouts.app')

@section('title', 'Pesanan Saya - TechCatalog')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-zinc-900 tracking-tight">Pesanan Saya</h1>
        <p class="text-sm text-zinc-500 mt-1">Lihat semua transaksi dan status pesananmu di sini.</p>
    </div>

    <!-- ========== TAB FILTER STATUS ========== -->
    @php
        $currentStatus = request('status');
        $tabs = [
            ['key' => null,         'label' => 'Semua',      'color' => 'bg-zinc-900 text-white'],
            ['key' => 'pending',    'label' => 'Menunggu',   'color' => 'bg-amber-500 text-white'],
            ['key' => 'processing', 'label' => 'Diproses',   'color' => 'bg-sky-500 text-white'],
            ['key' => 'completed',  'label' => 'Selesai',    'color' => 'bg-emerald-500 text-white'],
            ['key' => 'cancelled',  'label' => 'Dibatalkan', 'color' => 'bg-rose-500 text-white'],
        ];
    @endphp

    <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-1 text-xs font-semibold">
        @foreach($tabs as $tab)
            <a href="{{ route('orders.index', $tab['key'] ? ['status' => $tab['key']] : []) }}"
               class="px-4 py-2 rounded-lg transition-all whitespace-nowrap {{ $currentStatus === $tab['key'] ? $tab['color'] : 'bg-white border border-zinc-200 text-zinc-600 hover:bg-zinc-50' }}">
                {{ $tab['label'] }}
            </a>
        @endforeach
    </div>

    <!-- ========== KONTEN ========== -->
    @if($orders->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-2xl border border-zinc-200/80 p-12 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-zinc-100 mb-5">
                <svg class="w-10 h-10 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-zinc-900 mb-2">Belum Ada Pesanan</h3>
            <p class="text-sm text-zinc-500 mb-6 max-w-sm mx-auto">
                @if($currentStatus)
                    Tidak ada pesanan dengan status ini.
                @else
                    Kamu belum pernah membuat pesanan. Yuk, mulai belanja!
                @endif
            </p>
            <a href="{{ route('home') }}" class="inline-block px-5 py-2.5 bg-zinc-900 text-white text-sm font-semibold rounded-xl hover:bg-zinc-800 transition-all">
                Mulai Belanja
            </a>
        </div>
    @else
        <!-- Daftar Pesanan -->
        <div class="space-y-4">
            @foreach($orders as $order)
                @php
                    $statusConfig = [
                        'pending'    => ['bg' => 'bg-amber-50',   'text' => 'text-amber-700',   'ring' => 'ring-amber-600/20',   'icon' => '⏳', 'label' => 'Menunggu Konfirmasi'],
                        'processing' => ['bg' => 'bg-sky-50',     'text' => 'text-sky-700',     'ring' => 'ring-sky-600/20',     'icon' => '⚙️', 'label' => 'Sedang Diproses'],
                        'completed'  => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'ring' => 'ring-emerald-600/20', 'icon' => '✅', 'label' => 'Selesai'],
                        'cancelled'  => ['bg' => 'bg-rose-50',    'text' => 'text-rose-700',    'ring' => 'ring-rose-600/20',    'icon' => '❌', 'label' => 'Dibatalkan'],
                    ];
                    $s = $statusConfig[$order->status] ?? $statusConfig['pending'];
                @endphp

                <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs overflow-hidden hover:shadow-md transition-shadow">

                    <!-- Header: Toko + Status -->
                    <div class="px-5 py-3 border-b border-zinc-100 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded bg-zinc-900 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </span>
                            <div>
                                <p class="text-xs font-bold text-zinc-900">TechCatalog Official</p>
                                <p class="text-[10px] text-zinc-400">{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold ring-1 {{ $s['bg'] }} {{ $s['text'] }} {{ $s['ring'] }}">
                            {{ $s['icon'] }} {{ $s['label'] }}
                        </span>
                    </div>

                    <!-- Produk-produk dalam pesanan -->
                    <div class="divide-y divide-zinc-50">
                        @foreach($order->items as $item)
                            <div class="px-5 py-3 flex items-center gap-3">
                                <div class="w-14 h-14 rounded-lg bg-zinc-100 overflow-hidden shrink-0 border border-zinc-200">
                                    <img src="{{ $item->product->image_url ?? '' }}" alt="{{ $item->product->name ?? '' }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-zinc-900 truncate">{{ $item->product->name ?? 'Produk' }}</p>
                                    <p class="text-[11px] text-zinc-500 mt-0.5">Jumlah: <span class="font-mono font-bold text-zinc-700">{{ $item->quantity }}x</span></p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-xs font-bold font-mono text-zinc-900">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Footer: Total + Aksi -->
                    <div class="px-5 py-4 bg-zinc-50/60 border-t border-zinc-100">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <!-- Total -->
                            <div class="flex items-center gap-2 text-xs">
                                <span class="text-zinc-500">Total Pesanan:</span>
                                <span class="text-base font-bold font-mono text-zinc-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                @if($order->payment_status === 'paid')
                                    <span class="text-[10px] font-bold text-emerald-600">• Lunas</span>
                                @else
                                    <span class="text-[10px] font-bold text-amber-600">• Belum Lunas</span>
                                @endif
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="flex items-center gap-2">
                                <a href="{{ route('orders.receipt', $order->order_number) }}" 
                                   class="px-3.5 py-2 bg-white border border-zinc-200 text-zinc-700 text-[11px] font-semibold rounded-lg hover:bg-zinc-50 transition-all">
                                    Lihat Nota
                                </a>

                                @if($order->status === 'pending')
                                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                onclick="return confirm('Yakin ingin membatalkan pesanan ini?')"
                                                class="px-3.5 py-2 bg-white border border-rose-200 text-rose-600 text-[11px] font-semibold rounded-lg hover:bg-rose-50 transition-all">
                                            Batalkan
                                        </button>
                                    </form>
                                @endif

                                @if($order->status === 'completed' && $order->items->first() && $order->items->first()->product)
                                    <a href="{{ route('products.show', $order->items->first()->product->slug) }}" 
                                       class="px-3.5 py-2 bg-zinc-900 text-white text-[11px] font-semibold rounded-lg hover:bg-zinc-800 transition-all">
                                        Beli Lagi
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif

</div>
@endsection