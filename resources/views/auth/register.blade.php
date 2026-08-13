<x-guest-layout>
    <x-slot name="title">Daftar Member</x-slot>

    <div class="bg-slate-900 border border-white/10 rounded-3xl p-8">
        <h2 class="text-2xl font-bold text-white mb-1">Daftar Member 🏓</h2>
        <p class="text-white/50 text-sm mb-6">Bergabunglah dengan komunitas Padel YUK</p>

        <div class="bg-amber-500/10 border border-amber-500/30 text-amber-300 rounded-xl px-4 py-3 text-sm mb-6 flex items-start gap-2">
            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Pendaftaran memerlukan persetujuan admin sebelum Anda bisa login ke area komunitas.</span>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-white/70 mb-1.5">Nama Lengkap <span class="text-red-400">*</span></label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name"
                       class="w-full bg-slate-800 border @error('name') border-red-500 @else border-white/10 @enderror text-white placeholder-white/30 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-colors"
                       placeholder="Nama lengkap Anda">
                @error('name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-white/70 mb-1.5">Email <span class="text-red-400">*</span></label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                       class="w-full bg-slate-800 border @error('email') border-red-500 @else border-white/10 @enderror text-white placeholder-white/30 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-colors"
                       placeholder="email@contoh.com">
                @error('email') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-white/70 mb-1.5">Nomor HP / WhatsApp</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" autocomplete="tel"
                       class="w-full bg-slate-800 border @error('phone') border-red-500 @else border-white/10 @enderror text-white placeholder-white/30 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-colors"
                       placeholder="08xxxxxxxxxx">
                @error('phone') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-white/70 mb-1.5">Domisili / Alamat</label>
                <input id="address" type="text" name="address" value="{{ old('address') }}"
                       class="w-full bg-slate-800 border @error('address') border-red-500 @else border-white/10 @enderror text-white placeholder-white/30 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-colors"
                       placeholder="Contoh: Rungkut, Surabaya">
                @error('address') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-white/70 mb-1.5">Password <span class="text-red-400">*</span></label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                           class="w-full bg-slate-800 border @error('password') border-red-500 @else border-white/10 @enderror text-white placeholder-white/30 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-colors"
                           placeholder="Min 8 karakter">
                    @error('password') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-white/70 mb-1.5">Konfirmasi <span class="text-red-400">*</span></label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                           class="w-full bg-slate-800 border border-white/10 text-white placeholder-white/30 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-colors"
                           placeholder="Ulangi password">
                </div>
            </div>

            <button type="submit"
                    class="w-full btn-primary font-bold text-white py-3 rounded-xl text-sm shadow-lg shadow-green-500/20 mt-2 flex items-center justify-center gap-2">
                Kirim Pendaftaran
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-white/50">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-green-400 hover:text-green-300 font-medium transition-colors">Masuk di sini</a>
        </p>
    </div>
</x-guest-layout>
