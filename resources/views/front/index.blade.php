@extends('layouts.app')

@section('title', 'TechCatalog - Storefront')

@section('content')
<section class="border-b border-zinc-200/80 bg-white py-8 sm:py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto text-center space-y-3">
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-medium bg-zinc-100 text-zinc-700 mb-2">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            TechCatalog Storefront
        </div>
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold tracking-tight text-zinc-900">Essential Tech Gear</h1>
        <p class="max-w-lg mx-auto text-xs sm:text-sm text-zinc-500 font-normal leading-relaxed px-2">
            Perlengkapan kerja dan hardware esensial dengan desain bersih, modern, dan fungsionalitas tinggi.
        </p>
        
        <div class="pt-3 max-w-lg mx-auto">
            <form method="GET" action="{{ route('home') }}" class="flex items-center gap-2">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <div class="relative w-full">
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}" 
                           placeholder="Cari produk..." 
                           class="w-full pl-9 pr-4 py-2.5 bg-zinc-50 border border-zinc-200 rounded-xl text-xs text-zinc-900 placeholder-zinc-400 focus:bg-white focus:ring-1 focus:ring-zinc-900 focus:border-zinc-900 focus:outline-none transition-all shadow-2xs">
                    <svg class="w-4 h-4 text-zinc-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <button type="submit" class="bg-zinc-900 text-white font-semibold px-4 sm:px-5 py-2.5 rounded-xl text-xs hover:bg-zinc-800 active:scale-[0.98] transition-all shadow-2xs shrink-0">
                    Cari
                </button>
                @if($search || $categorySlug)
                    <a href="{{ route('home') }}" class="p-2.5 bg-zinc-100 text-zinc-600 rounded-xl text-xs hover:bg-zinc-200 transition-all shrink-0" title="Reset Filter">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </form>
        </div>
    </div>
</section>

<div class="bg-zinc-50 border-b border-zinc-200/80 sticky top-14 md:top-16 z-40 backdrop-blur-md bg-zinc-50/90">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2.5 sm:py-3">
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none text-xs no-scrollbar">
            <a href="{{ route('home', array_filter(['search' => $search])) }}" 
               class="px-3 py-1.5 rounded-lg font-semibold shrink-0 transition-all {{ empty($categorySlug) ? 'bg-zinc-900 text-white shadow-2xs' : 'bg-white border border-zinc-200 text-zinc-600 hover:bg-zinc-100' }}">
                Semua
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('home', array_filter(['category' => $cat->slug, 'search' => $search])) }}" 
                   class="px-3 py-1.5 rounded-lg font-semibold shrink-0 transition-all {{ $categorySlug === $cat->slug ? 'bg-zinc-900 text-white shadow-2xs' : 'bg-white border border-zinc-200 text-zinc-600 hover:bg-zinc-100' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-6 sm:py-10">

    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6">
        @forelse($products as $product)
            <div class="group relative rounded-2xl bg-white p-2.5 sm:p-3.5 border border-zinc-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:shadow-[0_12px_24px_-8px_rgba(0,0,0,0.08)] hover:border-zinc-300 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="relative aspect-square w-full overflow-hidden rounded-xl bg-zinc-100/80">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-500 ease-out">
                    </div>

                    <div class="mt-2.5 sm:mt-3.5 space-y-1 px-0.5 sm:px-1">
                        <h3 class="text-[11px] sm:text-xs font-bold text-zinc-900 group-hover:text-zinc-600 transition-colors line-clamp-2 leading-tight">
                            <a href="{{ route('products.show', $product->slug) }}">
                                {{ $product->name }}
                            </a>
                        </h3>
                        <p class="text-[10px] sm:text-[11px] text-zinc-500 line-clamp-2 font-normal leading-snug">
                            {{ $product->description }}
                        </p>
                    </div>
                </div>

                <div class="mt-3 sm:mt-4 pt-2.5 sm:pt-3 border-t border-zinc-100 px-0.5 sm:px-1 space-y-2">
                    <div class="flex items-center justify-between gap-1">
                        <span class="text-[9px] sm:text-[10px] text-zinc-400 font-semibold uppercase">Harga</span>
                        <span class="font-mono text-[11px] sm:text-sm font-bold text-zinc-900 whitespace-nowrap">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 gap-1.5 pt-0.5">
                        @if($product->stock > 0)
                            <form action="{{ route('cart.add', $product->slug) }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full py-1.5 sm:py-2 px-2 rounded-lg sm:rounded-xl bg-zinc-100 hover:bg-zinc-200 active:scale-95 text-zinc-800 text-[10px] sm:text-[11px] font-semibold transition-all flex items-center justify-center gap-1 shadow-2xs" title="Tambah ke Keranjang">
                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    <span>+ Keranjang</span>
                                </button>
                            </form>
                        @else
                            <button disabled class="w-full py-1.5 sm:py-2 px-2 rounded-lg sm:rounded-xl bg-zinc-100 text-zinc-400 text-[10px] sm:text-[11px] font-semibold cursor-not-allowed">
                                Habis
                            </button>
                        @endif

                        <a href="{{ route('products.show', $product->slug) }}" class="w-full py-1.5 sm:py-2 px-2 rounded-lg sm:rounded-xl bg-zinc-900 hover:bg-zinc-800 active:scale-95 text-white text-[10px] sm:text-[11px] font-semibold transition-all text-center flex items-center justify-center shadow-2xs">
                            Detail &rarr;
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white p-12 text-center rounded-2xl border border-zinc-200/80 text-zinc-400 text-xs space-y-2">
                <span class="text-3xl block">🔍</span>
                <p class="font-semibold text-zinc-700">Tidak ada produk yang cocok dengan pencarian atau filter Anda.</p>
                <a href="{{ route('home') }}" class="inline-block mt-2 px-4 py-2 rounded-xl bg-zinc-900 text-white text-xs font-semibold hover:bg-zinc-800">
                    Lihat Semua Produk
                </a>
            </div>
        @endforelse
    </div>

    <div class="mt-8 sm:mt-10">
        {{ $products->links() }}
    </div>

</div>
@endsection