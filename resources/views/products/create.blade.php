@extends('layouts.app')

@section('title', 'Tambah Produk - Admin')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.products.index') }}" class="p-2 bg-white border border-zinc-200 rounded-xl hover:bg-zinc-50 transition-colors shadow-2xs">
            <svg class="w-5 h-5 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 tracking-tight">Tambah Produk Baru</h1>
            <p class="text-sm text-zinc-500 mt-1">Masukkan detail produk untuk ditambahkan ke katalog.</p>
        </div>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-zinc-200/80 shadow-sm p-6 sm:p-8 space-y-6">
        @csrf

        <!-- Input Nama & Kategori -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="name" class="block text-sm font-semibold text-zinc-900 mb-2">Nama Produk <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                    class="w-full px-4 py-2.5 bg-zinc-50/50 border border-zinc-200 rounded-xl text-sm text-zinc-900 placeholder-zinc-400 focus:bg-white focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 focus:outline-none transition-all shadow-2xs @error('name') border-rose-500 focus:border-rose-500 focus:ring-rose-500/10 @enderror" 
                    placeholder="Misal: Keyboard Mechanical Keychron K2">
                @error('name') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="category_id" class="block text-sm font-semibold text-zinc-900 mb-2">Kategori Produk</label>
                <select name="category_id" id="category_id" class="w-full px-4 py-2.5 bg-zinc-50/50 border border-zinc-200 rounded-xl text-sm text-zinc-900 focus:bg-white focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 focus:outline-none transition-all shadow-2xs cursor-pointer">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Input Deskripsi -->
        <div>
            <label for="description" class="block text-sm font-semibold text-zinc-900 mb-2">Deskripsi Produk</label>
            <textarea name="description" id="description" rows="4" 
                class="w-full px-4 py-2.5 bg-zinc-50/50 border border-zinc-200 rounded-xl text-sm text-zinc-900 placeholder-zinc-400 focus:bg-white focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 focus:outline-none transition-all shadow-2xs @error('description') border-rose-500 focus:border-rose-500 focus:ring-rose-500/10 @enderror" 
                placeholder="Tuliskan spesifikasi dan detail produk...">{{ old('description') }}</textarea>
            @error('description') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Input Harga -->
            <div>
                <label for="price" class="block text-sm font-semibold text-zinc-900 mb-2">Harga (Rp) <span class="text-rose-500">*</span></label>
                <input type="number" name="price" id="price" value="{{ old('price') }}" required min="0" 
                    class="w-full px-4 py-2.5 bg-zinc-50/50 border border-zinc-200 rounded-xl text-sm text-zinc-900 font-mono placeholder-zinc-400 focus:bg-white focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 focus:outline-none transition-all shadow-2xs @error('price') border-rose-500 focus:border-rose-500 focus:ring-rose-500/10 @enderror" 
                    placeholder="0">
                @error('price') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <!-- Input Stok -->
            <div>
                <label for="stock" class="block text-sm font-semibold text-zinc-900 mb-2">Stok <span class="text-rose-500">*</span></label>
                <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" required min="0" 
                    class="w-full px-4 py-2.5 bg-zinc-50/50 border border-zinc-200 rounded-xl text-sm text-zinc-900 font-mono placeholder-zinc-400 focus:bg-white focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 focus:outline-none transition-all shadow-2xs @error('stock') border-rose-500 focus:border-rose-500 focus:ring-rose-500/10 @enderror" 
                    placeholder="0">
                @error('stock') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Input Gambar -->
        <div>
            <label for="image" class="block text-sm font-semibold text-zinc-900 mb-2">Foto Produk</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-zinc-300 border-dashed rounded-xl hover:bg-zinc-50 transition-colors cursor-pointer" onclick="document.getElementById('image').click()">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-zinc-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-zinc-600 justify-center">
                        <span class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                            Upload file
                        </span>
                        <p class="pl-1">atau drag and drop</p>
                    </div>
                    <p class="text-xs text-zinc-500">PNG, JPG, GIF up to 2MB</p>
                </div>
            </div>
            <input type="file" name="image" id="image" accept="image/*" class="sr-only">
            @error('image') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-zinc-100">
            <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-zinc-700 bg-white border border-zinc-200 hover:bg-zinc-50 transition-all">Batal</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-zinc-900 hover:bg-zinc-800 active:scale-[0.98] transition-all shadow-2xs">
                Simpan Produk
            </button>
        </div>
    </form>
</div>
@endsection
