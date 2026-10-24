@extends('layouts.app')

@section('title', 'Sign Up - TechCatalog')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        
        <!-- Card Register -->
        <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-sm p-8">
            
            <!-- Judul -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-zinc-900 tracking-tight">Create Account</h1>
                <p class="text-xs text-zinc-500 mt-1">Daftar untuk mulai berbelanja di TechCatalog</p>
            </div>

            <!-- Form Register -->
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-zinc-700 mb-2">
                        Full Name
                    </label>
                    <input id="name" 
                           type="text" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           autofocus
                           placeholder="Nama lengkap kamu"
                           class="w-full px-4 py-3 bg-white border border-zinc-200 rounded-xl text-sm text-zinc-800 placeholder-zinc-400 focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 focus:outline-none transition-all @error('name') border-rose-300 @enderror">
                    @error('name') <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-zinc-700 mb-2">
                        Email
                    </label>
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required
                           placeholder="nama@email.com"
                           class="w-full px-4 py-3 bg-white border border-zinc-200 rounded-xl text-sm text-zinc-800 placeholder-zinc-400 focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 focus:outline-none transition-all @error('email') border-rose-300 @enderror">
                    @error('email') <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-zinc-700 mb-2">
                        Password
                    </label>
                    <input id="password" 
                           type="password" 
                           name="password" 
                           required
                           placeholder="Minimal 8 karakter"
                           class="w-full px-4 py-3 bg-white border border-zinc-200 rounded-xl text-sm text-zinc-800 placeholder-zinc-400 focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 focus:outline-none transition-all @error('password') border-rose-300 @enderror">
                    @error('password') <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password-confirm" class="block text-xs font-semibold text-zinc-700 mb-2">
                        Confirm Password
                    </label>
                    <input id="password-confirm" 
                           type="password" 
                           name="password_confirmation" 
                           required
                           placeholder="Ulangi password"
                           class="w-full px-4 py-3 bg-white border border-zinc-200 rounded-xl text-sm text-zinc-800 placeholder-zinc-400 focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 focus:outline-none transition-all">
                </div>

                <!-- Tombol Sign Up -->
                <button type="submit" 
                        class="w-full bg-zinc-900 hover:bg-zinc-800 text-white text-sm font-semibold py-3.5 rounded-xl shadow-sm active:scale-[0.98] transition-all">
                    Sign Up
                </button>
            </form>

            <!-- Link Login -->
            <p class="text-center text-xs text-zinc-500 mt-6">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-bold text-zinc-900 hover:underline">Sign In</a>
            </p>

        </div>

    </div>
</div>
@endsection