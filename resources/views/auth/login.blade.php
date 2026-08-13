<x-guest-layout>
    <x-slot name="title">Masuk ke Akun</x-slot>

    <div class="bg-slate-900 border border-white/10 rounded-3xl p-8">
        <h2 class="text-2xl font-bold text-white mb-1">Selamat Datang 👋</h2>
        <p class="text-white/50 text-sm mb-6">Masuk ke akun komunitas Anda</p>

        {{-- Success message --}}
        @if(session('success'))
            <div class="mb-5 bg-green-500/15 border border-green-500/30 text-green-300 rounded-xl px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Session Status --}}
        @if (session('status'))
            <div class="mb-5 bg-blue-500/15 border border-blue-500/30 text-blue-300 rounded-xl px-4 py-3 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-white/70 mb-1.5">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                       class="w-full bg-slate-800 border @error('email') border-red-500 @else border-white/10 @enderror text-white placeholder-white/30 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-colors"
                       placeholder="email@contoh.com">
                @error('email')
                    <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-sm font-medium text-white/70">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-green-400 hover:text-green-300 transition-colors">Lupa password?</a>
                    @endif
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="w-full bg-slate-800 border @error('password') border-red-500 @else border-white/10 @enderror text-white placeholder-white/30 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-colors"
                       placeholder="••••••••">
                @error('password')
                    <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-2">
                <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-white/20 bg-slate-800 text-green-500 focus:ring-green-500 focus:ring-offset-slate-900">
                <label for="remember_me" class="text-sm text-white/60 cursor-pointer">Ingat saya</label>
            </div>

            <button type="submit"
                    class="w-full btn-primary font-bold text-white py-3 rounded-xl text-sm shadow-lg shadow-green-500/20 flex items-center justify-center gap-2">
                Masuk
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-white/50">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-green-400 hover:text-green-300 font-medium transition-colors">Daftar sekarang</a>
        </p>
    </div>

    <div class="mt-4 text-center">
        <a href="{{ route('match.create') }}" class="text-sm text-white/40 hover:text-white/70 transition-colors">
            → Pakai kalkulator skor tanpa login
        </a>
    </div>
</x-guest-layout>
