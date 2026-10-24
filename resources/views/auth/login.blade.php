@extends('layouts.app')

@section('title', 'Login - TechCatalog')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        
        <!-- Card Login -->
        <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-sm p-8">
            
            <!-- Judul -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-zinc-900 tracking-tight">Login</h1>
                <p class="text-xs text-zinc-500 mt-1">Masuk ke akun TechCatalog kamu</p>
            </div>

            <!-- Form Login -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

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
                           autofocus
                           placeholder="nama@email.com"
                           class="w-full px-4 py-3 bg-white border border-zinc-200 rounded-xl text-sm text-zinc-800 placeholder-zinc-400 focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 focus:outline-none transition-all @error('email') border-rose-300 @enderror">
                    @error('email')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
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
                           placeholder="••••••••"
                           class="w-full px-4 py-3 bg-white border border-zinc-200 rounded-xl text-sm text-zinc-800 placeholder-zinc-400 focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 focus:outline-none transition-all @error('password') border-rose-300 @enderror">
                    @error('password')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me + Lupa Password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center cursor-pointer">
                        <input id="remember" 
                               type="checkbox" 
                               name="remember" 
                               {{ old('remember') ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900/20">
                        <span class="ml-2 text-xs text-zinc-600">Ingat saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-zinc-500 hover:text-zinc-900 transition-colors">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <!-- Tombol Login -->
                <button type="submit" 
                        class="w-full bg-zinc-900 hover:bg-zinc-800 text-white text-sm font-semibold py-3.5 rounded-xl shadow-sm active:scale-[0.98] transition-all">
                    Login
                </button>
            </form>

            <!-- Link Daftar -->
            @if (Route::has('register'))
                <p class="text-center text-xs text-zinc-500 mt-6">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="font-bold text-zinc-900 hover:underline">Sign Up</a>
                </p>
            @endif

        </div>

    </div>
</div>
@endsection