<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>@yield('title', 'TechCatalog - Stealth E-Commerce')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'Geist Mono', monospace; }
        .dropdown-panel { display: none; }
        .dropdown-panel.show { display: block; }
        input, select, textarea { font-size: 16px !important; }
        @media (min-width: 640px) {
            input, select, textarea { font-size: inherit !important; }
        }
    </style>
</head>
<body class="bg-[#fafafa] text-zinc-800 antialiased min-h-screen flex flex-col justify-between selection:bg-zinc-900 selection:text-white">

    @php
        $cartUser = auth()->user();
        $navCartCount = $cartUser ? \App\Models\Cart::where('user_id', $cartUser->id)->sum('quantity') : 0;
    @endphp

    <!-- HEADER NAVBAR -->
    <header class="bg-white/80 backdrop-blur-md border-b border-zinc-200/80 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 h-14 md:h-16 flex items-center justify-between gap-2">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-base sm:text-lg text-zinc-900 tracking-tight shrink-0">
                <span class="w-2.5 h-2.5 rounded-full bg-zinc-900 inline-block"></span>
                <span>TechCatalog</span>
            </a>

            <!-- MENU KANAN -->
            <nav class="flex items-center gap-1.5 text-xs font-semibold">

                <!-- Storefront (desktop) -->
                <a href="{{ route('home') }}" class="hidden md:inline-block px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('home') ? 'bg-zinc-100 text-zinc-900 font-bold' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50' }}">
                    Storefront
                </a>

                <!-- Keranjang -->
                @auth
                    <a href="{{ route('cart.index') }}" class="px-2.5 py-1.5 rounded-lg transition-all flex items-center gap-1.5 {{ request()->routeIs('cart.*') ? 'bg-zinc-100 text-zinc-900 font-bold' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50' }}" title="Keranjang Belanja">
                        <span class="text-base">🛒</span>
                        @if($navCartCount > 0)
                        <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-mono font-bold leading-none text-white bg-zinc-900 rounded-full">{{ $navCartCount }}</span>
                        @endif
                    </a>
                    @endauth

                <!-- Admin Dropdown -->
                @auth
                    @if(auth()->user()->isAdmin())
                        <div class="relative">
                            <button type="button" onclick="toggleDropdown('adminDropdown')" 
                                    class="px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.*') ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50' }}">
                                Admin ▾
                            </button>
                            <div id="adminDropdown" class="dropdown-panel absolute right-0 mt-2 w-56 bg-white rounded-xl border border-zinc-200 shadow-lg py-2 z-50">
                                <a href="{{ route('admin.products.index') }}" class="block px-4 py-2 text-xs text-zinc-700 hover:bg-zinc-50">
                                    Kelola Produk
                                </a>
                                <a href="{{ route('admin.orders.index') }}" class="block px-4 py-2 text-xs text-zinc-700 hover:bg-zinc-50">
                                    Kelola Pesanan
                                </a>
                                <div class="border-t border-zinc-100 my-1"></div>
                                <a href="{{ route('admin.products.create') }}" class="block px-4 py-2 text-xs text-zinc-700 hover:bg-zinc-50 font-semibold">
                                    Tambah Produk
                                </a>
                            </div>
                        </div>
                    @endif
                @endauth

                <!-- User Dropdown -->
                @auth
                    <div class="relative">
                        <button type="button" onclick="toggleDropdown('userDropdown')" 
                                class="px-3 py-1.5 rounded-lg transition-all text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50 flex items-center gap-1.5">
                            <span class="w-6 h-6 rounded-full bg-zinc-900 text-white text-[10px] font-bold flex items-center justify-center">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span class="hidden lg:inline">{{ Str::limit(auth()->user()->name, 12) }}</span>
                        </button>
                        <div id="userDropdown" class="dropdown-panel absolute right-0 mt-2 w-48 bg-white rounded-xl border border-zinc-200 shadow-lg py-2 z-50">
                            <div class="px-4 py-2 border-b border-zinc-100">
                                <p class="text-xs font-bold text-zinc-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-[10px] text-zinc-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-xs text-zinc-700 hover:bg-zinc-50">Pesanan Saya</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 font-semibold">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                <!-- Guest -->
                @guest
                    <a href="{{ route('login') }}" class="px-3 py-1.5 rounded-lg transition-all text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50 font-semibold">
                        Masuk
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-3 py-1.5 rounded-lg transition-all bg-zinc-900 text-white hover:bg-zinc-800 font-semibold">
                            Daftar
                        </a>
                    @endif
                @endguest
            </nav>
        </div>
    </header>

    <!-- KONTEN UTAMA -->
    <main class="mb-auto">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 mt-4 sm:mt-6">
                <div class="p-3 text-xs font-medium text-emerald-800 bg-emerald-50/80 border border-emerald-200/80 rounded-xl shadow-2xs">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 mt-4 sm:mt-6">
                <div class="p-3 text-xs font-medium text-rose-800 bg-rose-50/80 border border-rose-200/80 rounded-xl shadow-2xs">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-zinc-200/80 mt-12 sm:mt-16 py-6 text-center text-xs text-zinc-400">
        <p>&copy; {{ date('Y') }} TechCatalog System. Stealth Minimalist Commerce.</p>
    </footer>

    <!-- JavaScript -->
    <script>
        function toggleDropdown(id) {
            document.querySelectorAll('.dropdown-panel').forEach(el => {
                if (el.id !== id) el.classList.remove('show');
            });
            document.getElementById(id).classList.toggle('show');
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.relative')) {
                document.querySelectorAll('.dropdown-panel').forEach(el => el.classList.remove('show'));
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.dropdown-panel').forEach(el => el.classList.remove('show'));
            }
        });
    </script>

</body>
</html>