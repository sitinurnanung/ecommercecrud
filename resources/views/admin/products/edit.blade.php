@extends('layouts.app')

@section('title', 'Admin - Edit Produk')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-zinc-900 tracking-tight">Edit Produk</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Perbarui rincian data produk.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-zinc-500 hover:text-zinc-900 transition">
            &larr; Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="mb-5 p-3.5 text-xs font-medium text-rose-800 bg-rose-50/80 border border-rose-200/80 rounded-xl">
            <p class="font-bold mb-1">Ada kesalahan pada form:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-6 rounded-2xl border border-zinc-200/80 shadow-2xs">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-semibold text-zinc-700 mb-1.5">Nama Produk <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-zinc-200 text-xs text-zinc-900 focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 focus:outline-none transition-all">
                @error('name')
                    <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="category_id" class="block text-xs font-semibold text-zinc-700 mb-1.5">Kategori <span class="text-rose-500">*</span></label>
                <select name="category_id" id="category_id" class="w-full px-3.5 py-2.5 rounded-xl border border-zinc-200 text-xs text-zinc-900 bg-white focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 focus:outline-none transition-all">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block text-xs font-semibold text-zinc-700 mb-1.5">Harga (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" name="price" id="price" value="{{ old('price', (int) $product->price) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-zinc-200 text-xs font-mono text-zinc-900 focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 focus:outline-none transition-all">
                    @error('price')
                        <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="stock" class="block text-xs font-semibold text-zinc-700 mb-1.5">Stok <span class="text-rose-500">*</span></label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-zinc-200 text-xs font-mono text-zinc-900 focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 focus:outline-none transition-all">
                    @error('stock')
                        <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold text-zinc-700 mb-1.5">Deskripsi Produk <span class="text-rose-500">*</span></label>
                <textarea name="description" id="description" rows="4" class="w-full px-3.5 py-2.5 rounded-xl border border-zinc-200 text-xs text-zinc-900 focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 focus:outline-none transition-all">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Foto Produk Saat Ini</label>
                <div class="flex items-center gap-3 mb-3">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-16 h-16 rounded-xl object-cover border border-zinc-200">
                    <span class="text-[11px] text-zinc-400">Kosongkan jika tidak ingin mengubah foto.</span>
                </div>

                <label for="image" class="block text-xs font-semibold text-zinc-700 mb-1.5">Upload Foto Baru (Opsional)</label>
                <input type="file" name="image" id="image" accept="image/*" class="w-full px-3 py-2 border border-zinc-200 rounded-xl text-xs bg-zinc-50/50 text-zinc-600 focus:outline-none">
                @error('image')
                    <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-2.5 pt-4 border-t border-zinc-100">
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-xl bg-zinc-100 text-zinc-700 font-semibold text-xs hover:bg-zinc-200 transition">Batal</a>
                <button type="submit" class="px-4 py-2 rounded-xl bg-zinc-900 text-white font-semibold text-xs hover:bg-zinc-800 active:scale-[0.98] transition shadow-2xs">Perbarui Produk</button>
            </div>

        </form>
    </div>

</div>
@endsection