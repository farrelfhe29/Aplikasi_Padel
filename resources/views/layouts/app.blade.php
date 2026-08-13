<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>
    <meta name="description" content="{{ $description ?? 'Platform komunitas padel Padel YUK — kalkulator skor, jadwal event, dan info komunitas.' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-green { background: linear-gradient(135deg, #16a34a 0%, #15803d 50%, #166534 100%); }
        .gradient-dark { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); }
        .glass { background: rgba(255,255,255,0.08); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.15); }
        .glass-dark { background: rgba(15,23,42,0.8); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.08); }
        .card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 20px 40px -12px rgba(0,0,0,0.3); }
        .btn-primary { background: linear-gradient(135deg, #16a34a, #15803d); transition: all 0.2s ease; }
        .btn-primary:hover { background: linear-gradient(135deg, #15803d, #166534); transform: translateY(-1px); box-shadow: 0 8px 20px -4px rgba(22, 163, 74, 0.5); }
        .nav-link { position: relative; }
        .nav-link::after { content: ''; position: absolute; bottom: -2px; left: 0; right: 0; height: 2px; background: #16a34a; transform: scaleX(0); transition: transform 0.2s; }
        .nav-link:hover::after { transform: scaleX(1); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full bg-slate-950 text-white">

{{-- ─── Navbar ─────────────────────────────────────────────────── --}}
<nav class="sticky top-0 z-50 glass-dark border-b border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center group">
                <div class="w-20 h-20 overflow-hidden group-hover:scale-110 transition-transform">
                    <img src="{{ asset('images/Yuk-P.png') }}" alt="Padel YUK" class="w-full h-full object-contain drop-shadow-md">
                </div>
            </a>

            {{-- Desktop Nav --}}
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('match.create') }}" class="nav-link flex items-center gap-1.5 text-sm font-medium text-white/80 hover:text-white transition-colors">
                    <svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Kalkulator Skor
                </a>

                <a href="{{ route('americano.create') }}" class="nav-link flex items-center gap-1.5 text-sm font-medium text-white/80 hover:text-white transition-colors">
                    <svg class="w-4 h-4 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    Americano
                </a>

                @auth
                    @if(auth()->user()->isApproved())
                        <a href="{{ route('community.events.index') }}" class="nav-link flex items-center gap-1.5 text-sm font-medium text-white/80 hover:text-white transition-colors">
                            <svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Komunitas
                        </a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.members.index') }}" class="nav-link flex items-center gap-1.5 text-sm font-medium text-amber-400 hover:text-amber-300 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Admin
                            </a>
                        @endif
                    @endif
                @endauth
            </div>

            {{-- Auth Buttons --}}
            <div class="flex items-center gap-3">
                @auth
                    <div class="hidden md:flex items-center gap-3">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 text-sm text-white/70 hover:text-white transition-colors">
                            <div class="w-8 h-8 rounded-full bg-green-500/20 border border-green-500/40 flex items-center justify-center text-green-400 font-bold text-xs">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden lg:block font-medium">{{ auth()->user()->name }}</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-white/50 hover:text-red-400 transition-colors px-2 py-1">
                                Keluar
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-white/70 hover:text-white transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-primary text-sm font-semibold px-4 py-2 rounded-lg text-white">Daftar</a>
                @endauth

                {{-- Mobile menu button --}}
                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-white/10 pt-4 space-y-2">
            <a href="{{ route('match.create') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-white/80 hover:bg-white/10 hover:text-white transition-colors">
                <svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Kalkulator Skor
            </a>
            <a href="{{ route('americano.create') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-white/80 hover:bg-orange-500/10 hover:text-orange-400 transition-colors">
                <svg class="w-4 h-4 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                Americano
            </a>
            @auth
                @if(auth()->user()->isApproved())
                    <a href="{{ route('community.events.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-white/80 hover:bg-white/10 hover:text-white transition-colors">Komunitas</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.members.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-amber-400 hover:bg-white/10 transition-colors">Admin Panel</a>
                    @endif
                @endif
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-white/80 hover:bg-white/10 hover:text-white transition-colors">Profil Saya</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-red-400 hover:bg-red-500/10 transition-colors">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-white/80 hover:bg-white/10 hover:text-white transition-colors">Masuk</a>
                <a href="{{ route('register') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold text-green-400 hover:bg-green-500/10 transition-colors">Daftar Sekarang</a>
            @endauth
        </div>
    </div>
</nav>

{{-- ─── Flash Messages ──────────────────────────────────────────── --}}
@if(session('success'))
    <div class="max-w-7xl mx-auto px-4 pt-4" id="flash-success">
        <div class="bg-green-500/15 border border-green-500/40 text-green-300 rounded-xl px-4 py-3 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
            <button onclick="document.getElementById('flash-success').remove()" class="text-green-400 hover:text-white shrink-0">✕</button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="max-w-7xl mx-auto px-4 pt-4" id="flash-error">
        <div class="bg-red-500/15 border border-red-500/40 text-red-300 rounded-xl px-4 py-3 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm">{{ session('error') }}</span>
            </div>
            <button onclick="document.getElementById('flash-error').remove()" class="text-red-400 hover:text-white shrink-0">✕</button>
        </div>
    </div>
@endif

{{-- ─── Main Content ────────────────────────────────────────────── --}}
<main>
    {{ $slot }}
</main>

{{-- ─── Footer ──────────────────────────────────────────────────── --}}
<footer class="border-t border-white/10 py-8 mt-16">
    <div class="max-w-7xl mx-auto px-4 text-center text-sm text-white/40">
        <p>&copy; {{ date('Y') }} <span class="text-green-400 font-medium">Padel YUK</span>. Komunitas Padel Surabaya.</p>
    </div>
</footer>

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>

{{ $scripts ?? '' }}
</body>
</html>
