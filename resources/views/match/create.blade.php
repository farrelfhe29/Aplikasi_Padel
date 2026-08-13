<x-app-layout>
    <x-slot name="title">Buat Match Baru — Padel YUK</x-slot>

    <div class="max-w-2xl mx-auto px-4 py-12">
        {{-- Back button --}}
        <div class="mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-white/50 hover:text-white transition-colors group">
                <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Home
            </a>
        </div>
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 bg-green-500/15 border border-green-500/30 text-green-400 text-xs font-semibold px-4 py-1.5 rounded-full mb-4 uppercase tracking-wider">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="4"/></svg>
                Kalkulator Skor Publik
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3">Setup Match Padel</h1>
            <p class="text-white/50">Isi nama pemain dan konfigurasi match Anda</p>
        </div>

        <div class="bg-slate-900 border border-white/10 rounded-3xl p-6 sm:p-8"
             x-data="{
                mode: 'double',
                format: 'best_of_3',
                goldenPoint: false,
                teamA: ['', ''],
                teamB: ['', ''],
                addPlayer(team) {
                    if (this[team].length < 2) this[team].push('');
                },
                removePlayer(team, index) {
                    if (this[team].length > 1) this[team].splice(index, 1);
                },
                get maxPlayers() { return this.mode === 'single' ? 1 : 2; }
             }"
             x-init="$watch('mode', val => {
                if (val === 'single') { teamA = [teamA[0] || '']; teamB = [teamB[0] || '']; }
                else { if (teamA.length < 2) teamA.push(''); if (teamB.length < 2) teamB.push(''); }
             })">

            <form method="POST" action="{{ route('match.store') }}">
                @csrf

                {{-- Mode --}}
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-white/70 mb-3 uppercase tracking-wider">Mode Pertandingan</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="mode" value="single" x-model="mode" class="peer sr-only" id="mode-single">
                            <div class="border-2 rounded-2xl p-4 text-center transition-all peer-checked:border-green-500 peer-checked:bg-green-500/10 border-white/10 hover:border-white/30">
                                <div class="text-2xl mb-1">🎾</div>
                                <div class="font-bold text-white">Single</div>
                                <div class="text-xs text-white/50">1 vs 1</div>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="mode" value="double" x-model="mode" class="peer sr-only" id="mode-double" checked>
                            <div class="border-2 rounded-2xl p-4 text-center transition-all peer-checked:border-green-500 peer-checked:bg-green-500/10 border-white/10 hover:border-white/30">
                                <div class="text-2xl mb-1">🏆</div>
                                <div class="font-bold text-white">Double</div>
                                <div class="text-xs text-white/50">2 vs 2</div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Format --}}
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-white/70 mb-3 uppercase tracking-wider">Format Match</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="format" value="best_of_1" x-model="format" class="peer sr-only" id="format-bo1">
                            <div class="border-2 rounded-2xl p-4 text-center transition-all peer-checked:border-green-500 peer-checked:bg-green-500/10 border-white/10 hover:border-white/30">
                                <div class="font-bold text-white text-lg">Bo1</div>
                                <div class="text-xs text-white/50">Best of 1 Set</div>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="format" value="best_of_3" x-model="format" class="peer sr-only" id="format-bo3" checked>
                            <div class="border-2 rounded-2xl p-4 text-center transition-all peer-checked:border-green-500 peer-checked:bg-green-500/10 border-white/10 hover:border-white/30">
                                <div class="font-bold text-white text-lg">Bo3</div>
                                <div class="text-xs text-white/50">Best of 3 Set</div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Golden Point Toggle --}}
                <div class="mb-8">
                    <div class="flex items-center justify-between bg-slate-800 border border-white/10 rounded-2xl px-5 py-4">
                        <div>
                            <div class="font-semibold text-white">Golden Point</div>
                            <div class="text-sm text-white/50">Saat deuce, poin berikutnya langsung menang game</div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="golden_point" value="0">
                            <input type="checkbox" name="golden_point" value="1" x-model="goldenPoint" id="golden-point" class="sr-only peer">
                            <div class="w-12 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                        </label>
                    </div>
                </div>

                {{-- Players --}}
                <div class="grid sm:grid-cols-2 gap-6 mb-8">

                    {{-- Team A --}}
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-7 h-7 bg-blue-500 rounded-lg flex items-center justify-center text-white text-xs font-black">A</div>
                            <span class="font-semibold text-white">Tim A</span>
                        </div>
                        <div class="space-y-3">
                            <template x-for="(player, index) in teamA" :key="index">
                                <div class="flex gap-2">
                                    <input type="text" :name="'team_a[]'" x-model="teamA[index]"
                                           :placeholder="'Pemain A' + (index + 1)"
                                           class="flex-1 bg-slate-800 border border-white/10 text-white placeholder-white/30 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                                    <button type="button" x-show="teamA.length > 1"
                                            @click="removePlayer('teamA', index)"
                                            class="p-3 text-white/30 hover:text-red-400 hover:bg-red-500/10 rounded-xl transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                        @error('team_a') <p class="text-red-400 text-xs mt-2">{{ $message }}</p> @enderror
                        @error('team_a.*') <p class="text-red-400 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>

                    {{-- Team B --}}
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-7 h-7 bg-red-500 rounded-lg flex items-center justify-center text-white text-xs font-black">B</div>
                            <span class="font-semibold text-white">Tim B</span>
                        </div>
                        <div class="space-y-3">
                            <template x-for="(player, index) in teamB" :key="index">
                                <div class="flex gap-2">
                                    <input type="text" :name="'team_b[]'" x-model="teamB[index]"
                                           :placeholder="'Pemain B' + (index + 1)"
                                           class="flex-1 bg-slate-800 border border-white/10 text-white placeholder-white/30 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
                                    <button type="button" x-show="teamB.length > 1"
                                            @click="removePlayer('teamB', index)"
                                            class="p-3 text-white/30 hover:text-red-400 hover:bg-red-500/10 rounded-xl transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                        @error('team_b') <p class="text-red-400 text-xs mt-2">{{ $message }}</p> @enderror
                        @error('team_b.*') <p class="text-red-400 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>

                @if($errors->any())
                    <div class="mb-6 bg-red-500/10 border border-red-500/30 text-red-400 rounded-xl p-4 text-sm">
                        <ul class="space-y-1">
                            @foreach($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <button type="submit"
                        class="w-full btn-primary font-bold text-white py-4 rounded-2xl text-lg flex items-center justify-center gap-3 shadow-xl shadow-green-500/20">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Mulai Match!
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
