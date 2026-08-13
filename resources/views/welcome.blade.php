<x-app-layout>
    <x-slot name="title">Padel YUK — Komunitas Padel Surabaya</x-slot>

    {{-- ─── Hero ─────────────────────────────────────────────────────────────── --}}
    <section class="relative overflow-hidden min-h-[90vh] flex items-center">
        {{-- Background --}}
        <div class="absolute inset-0 gradient-dark"></div>
        <div class="absolute inset-0" style="background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(22,163,74,0.18) 0%, transparent 70%);"></div>
        <div class="absolute top-20 right-0 w-96 h-96 bg-green-500/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-green-800/10 rounded-full blur-3xl"></div>

        {{-- Court lines decoration --}}
        <div class="absolute inset-0 opacity-5 pointer-events-none">
            <div class="absolute top-1/2 left-0 right-0 h-px bg-green-400"></div>
            <div class="absolute top-0 bottom-0 left-1/2 w-px bg-green-400"></div>
            <div class="absolute top-1/4 left-1/4 right-1/4 bottom-1/4 border border-green-400 rounded-full"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 w-full">
            <div class="text-center max-w-4xl mx-auto">
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 bg-green-500/15 border border-green-500/30 text-green-400 text-xs font-semibold px-4 py-1.5 rounded-full mb-6 uppercase tracking-wider">
                    <div class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></div>
                    Komunitas Padel #1 Surabaya
                </div>

                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold mb-6 leading-tight">
                    <span class="text-white">Padel</span><br>
                    <span class="bg-gradient-to-r from-green-400 to-emerald-300 bg-clip-text text-transparent">YUK</span>
                </h1>

                <p class="text-lg sm:text-xl text-white/60 max-w-2xl mx-auto mb-10 leading-relaxed">
                    Komunitas padel yang aktif dan bersemangat. Hitung skor pertandinganmu secara real-time, dan bergabunglah dengan komunitas kami untuk jadwal event seru!
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('match.create') }}"
                       class="group w-full sm:w-auto inline-flex items-center justify-center gap-3 btn-primary text-white font-bold px-8 py-4 rounded-2xl text-lg shadow-xl shadow-green-500/20">
                        <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Mulai Match
                    </a>
                    <a href="{{ route('americano.create') }}"
                       class="group w-full sm:w-auto inline-flex items-center justify-center gap-3 bg-orange-600 hover:bg-orange-700 text-white font-bold px-8 py-4 rounded-2xl text-lg shadow-xl shadow-orange-500/20 transition-all">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        Buat Americano
                    </a>
                </div>

                {{-- Stats --}}
                <div class="flex items-center justify-center gap-8 mt-16 pt-8 border-t border-white/10">
                    <div class="text-center">
                        <div class="text-3xl font-extrabold text-green-400">∞</div>
                        <div class="text-xs text-white/50 mt-1">Match Terhitung</div>
                    </div>
                    <div class="w-px h-10 bg-white/10"></div>
                    <div class="text-center">
                        <div class="text-3xl font-extrabold text-green-400">Free</div>
                        <div class="text-xs text-white/50 mt-1">Tanpa Biaya</div>
                    </div>
                    <div class="w-px h-10 bg-white/10"></div>
                    <div class="text-center">
                        <div class="text-3xl font-extrabold text-green-400">📱</div>
                        <div class="text-xs text-white/50 mt-1">Mobile Ready</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ─── Features ──────────────────────────────────────────────────────────── --}}
    <section class="py-20 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">Dua Fitur Utama</h2>
                <p class="text-white/50 max-w-xl mx-auto">Gratis untuk semua, komunitas untuk yang bergabung.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">

                {{-- Score Calculator Card --}}
                <div class="group relative bg-gradient-to-br from-slate-900 to-slate-800 border border-white/10 rounded-3xl p-8 card-hover overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-green-500/10 rounded-full blur-2xl group-hover:bg-green-500/20 transition-all"></div>
                    <div class="relative">
                        <div class="w-14 h-14 bg-green-500/20 border border-green-500/40 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div class="inline-block bg-green-500/20 text-green-400 text-xs font-bold px-3 py-1 rounded-full mb-3 uppercase tracking-wider">Publik • Gratis</div>
                        <h3 class="text-2xl font-bold text-white mb-3">Kalkulator Skor Padel</h3>
                        <p class="text-white/60 mb-6 leading-relaxed">Hitung skor pertandingan padel secara real-time. Mendukung sistem deuce, advantage, golden point, tiebreak, dan format Best of 3. Bisa dipakai tanpa login!</p>
                        <ul class="space-y-2 mb-8">
                            @foreach(['Single & Double match', 'Sistem deuce & advantage', 'Golden point (opsional)', 'Tombol undo & reset', 'Best of 1 / Best of 3'] as $f)
                                <li class="flex items-center gap-2 text-sm text-white/70">
                                    <svg class="w-4 h-4 text-green-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    {{ $f }}
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('match.create') }}" class="btn-primary inline-flex items-center gap-2 font-bold text-white px-6 py-3 rounded-xl">
                            Mulai Match
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Americano Card --}}
                <div class="group relative bg-gradient-to-br from-slate-900 to-slate-800 border border-orange-500/20 rounded-3xl p-8 card-hover overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-orange-500/10 rounded-full blur-2xl group-hover:bg-orange-500/20 transition-all"></div>
                    <div class="relative">
                        <div class="w-14 h-14 bg-orange-500/20 border border-orange-500/40 rounded-2xl flex items-center justify-center mb-6">
                            <span class="text-3xl">🏆</span>
                        </div>
                        <div class="inline-block bg-orange-500/20 text-orange-400 text-xs font-bold px-3 py-1 rounded-full mb-3 uppercase tracking-wider">Publik • Gratis</div>
                        <h3 class="text-2xl font-bold text-white mb-3">Americano</h3>
                        <p class="text-white/60 mb-6 leading-relaxed">Format turnamen sosial di mana pasangan bertukar setiap ronde. Poin dihitung per individu — siapa dengan poin terbanyak di akhir turnamen yang menang!</p>
                        <ul class="space-y-2 mb-8">
                            @foreach(['4 / 8 / 12 pemain', 'Rotasi pasangan otomatis', 'Live scoring per game', 'Leaderboard real-time', 'Semua ronde selesai otomatis'] as $f)
                                <li class="flex items-center gap-2 text-sm text-white/70">
                                    <svg class="w-4 h-4 text-orange-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    {{ $f }}
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('americano.create') }}" class="inline-flex items-center gap-2 font-bold text-white px-6 py-3 rounded-xl" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
                            Mulai Americano
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Community Card --}}
                <div class="group relative bg-gradient-to-br from-slate-900 to-slate-800 border border-white/10 rounded-3xl p-8 card-hover overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-blue-500/10 rounded-full blur-2xl group-hover:bg-blue-500/20 transition-all"></div>
                    <div class="relative">
                        <div class="w-14 h-14 bg-blue-500/20 border border-blue-500/40 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div class="inline-block bg-blue-500/20 text-blue-400 text-xs font-bold px-3 py-1 rounded-full mb-3 uppercase tracking-wider">Member • Perlu Daftar</div>
                        <h3 class="text-2xl font-bold text-white mb-3">Komunitas Member</h3>
                        <p class="text-white/60 mb-6 leading-relaxed">Bergabunglah sebagai member komunitas untuk mengakses jadwal event padel, daftar ikut pertandingan, dan terhubung dengan sesama pemain Surabaya.</p>
                        <ul class="space-y-2 mb-8">
                            @foreach(['Jadwal event & pertandingan', 'Daftar ikut event komunitas', 'Info venue & lokasi', 'Profil member', 'Disetujui admin'] as $f)
                                <li class="flex items-center gap-2 text-sm text-white/70">
                                    <svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    {{ $f }}
                                </li>
                            @endforeach
                        </ul>
                        @auth
                            @if(auth()->user()->isApproved())
                                <a href="{{ route('community.events.index') }}" class="inline-flex items-center gap-2 font-bold text-white bg-blue-600 hover:bg-blue-700 transition-colors px-6 py-3 rounded-xl">
                                    Lihat Event
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @else
                                <div class="inline-flex items-center gap-2 text-yellow-400 text-sm font-medium bg-yellow-500/10 px-4 py-3 rounded-xl">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Menunggu persetujuan admin
                                </div>
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 font-bold text-white bg-blue-600 hover:bg-blue-700 transition-colors px-6 py-3 rounded-xl">
                                Daftar Member
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ─── How it Works ───────────────────────────────────────────────────────── --}}
    <section class="py-16 border-t border-white/5">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-2xl sm:text-3xl font-bold text-white mb-3">Cara Pakai Kalkulator Skor</h2>
                <p class="text-white/50">Cepat, mudah, tanpa perlu daftar akun</p>
            </div>
            <div class="grid sm:grid-cols-3 gap-6">
                @foreach([
                    ['01', 'Isi Nama Pemain', 'Masukkan nama tim A dan tim B. Pilih mode Single atau Double, format Best of 1/3, dan atur opsi golden point.', 'text-green-400'],
                    ['02', 'Mulai Match', 'Tekan tombol "Tim A Point" atau "Tim B Point" setiap kali ada poin. Skor otomatis update real-time!', 'text-blue-400'],
                    ['03', 'Lihat Hasil', 'Setelah match selesai, lihat ringkasan skor lengkap per set. Bisa disimpan ke riwayat (opsional).', 'text-purple-400'],
                ] as [$num, $title, $desc, $color])
                    <div class="relative bg-slate-900 border border-white/10 rounded-2xl p-6 text-center">
                        <div class="text-5xl font-black {{ $color }} opacity-20 absolute top-4 right-4">{{ $num }}</div>
                        <div class="text-4xl font-black {{ $color }} mb-4">{{ $num }}</div>
                        <h3 class="font-bold text-white text-lg mb-2">{{ $title }}</h3>
                        <p class="text-white/50 text-sm leading-relaxed">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-app-layout>
