@extends('layouts.app')

@section('title', 'Admin - Kelola Produk')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-zinc-900 tracking-tight">Manajemen Produk</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Kelola entri katalog, ubah stok, harga, dan foto produk.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-semibold px-4 py-2 rounded-xl shadow-2xs active:scale-[0.98] transition-all">
            + Tambah Produk
        </a>
    </div>

    <div class="mb-6">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex gap-2 max-w-md">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari produk..." class="w-full px-3.5 py-2 bg-white border border-zinc-200 rounded-xl text-xs text-zinc-800 focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 focus:outline-none transition-all shadow-2xs">
            <button type="submit" class="px-4 py-2 bg-zinc-900 text-white rounded-xl text-xs font-semibold hover:bg-zinc-800 transition-all active:scale-[0.98]">Cari</button>
        </form>
    </div>

    <div class="w-full overflow-hidden rounded-xl border border-zinc-200/80 bg-white shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-600">
                <thead class="bg-zinc-50/80 text-[11px] font-semibold uppercase tracking-wider text-zinc-500 border-b border-zinc-200/80">
                    <tr>
                        <th class="py-3 px-4 w-14 text-center">Foto</th>
                        <th class="py-3 px-4">Nama Produk</th>
                        <th class="py-3 px-4">Slug / SEO</th>
                        <th class="py-3 px-4">Harga</th>
                        <th class="py-3 px-4">Stok</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-zinc-50/60 transition-colors group">
                            <!-- Kolom 1: Foto -->
                            <td class="py-2.5 px-4 text-center">
                                <div class="h-9 w-9 overflow-hidden rounded-lg border border-zinc-200/80 bg-zinc-100 mx-auto">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                </div>
                            </td>
                            
                            <!-- Kolom 2: Nama -->
                            <td class="py-2.5 px-4 font-semibold text-zinc-900 text-xs group-hover:text-indigo-600 transition-colors">
                                {{ $product->name }}
                            </td>
                            
                            <!-- Kolom 3: Slug -->
                            <td class="py-2.5 px-4 font-mono text-[11px] text-zinc-400">
                                {{ $product->slug }}
                            </td>
                            
                            <!-- Kolom 4: Harga -->
                            <td class="py-2.5 px-4 font-mono text-xs font-bold text-zinc-900">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            
                            <!-- Kolom 5: Stok -->
                            <td class="py-2.5 px-4">
                                @if($product->stock > 0)
                                    <span class="inline-flex items-center gap-1.5 rounded-md bg-emerald-50 px-2 py-0.5 text-[11px] font-medium text-emerald-700 ring-1 ring-emerald-600/20">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        {{ $product->stock }} unit
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-md bg-rose-50 px-2 py-0.5 text-[11px] font-medium text-rose-700 ring-1 ring-rose-600/20">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                        Habis
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Kolom 6: Aksi (Edit & Hapus) -->
                            <td class="py-2.5 px-4 text-center">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('admin.products.edit', $product->slug) }}" class="btn btn-warning btn-sm">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->slug) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-xs text-zinc-400">
                                Belum ada data produk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 bg-zinc-50/50 border-t border-zinc-200/80">
            {{ $products->links() }}
        </div>
    </div>

</div>
@endsection