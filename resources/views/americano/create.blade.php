<x-app-layout>
    <x-slot name="title">Buat Turnamen Americano — Padel YUK</x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-white/50 hover:text-white transition-colors text-sm mb-4">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
            <div class="flex items-center gap-3 mb-2">
                <span class="bg-orange-500/20 border border-orange-500/30 text-orange-400 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Americano</span>
            </div>
            <h1 class="text-3xl font-extrabold text-white">Buat Turnamen Americano 🏆</h1>
            <p class="text-white/50 mt-1.5 text-sm leading-relaxed">Setiap pemain bermain secara individual, pasangan dan lawan bertukar setiap ronde. Pemenang ditentukan dari total poin terbanyak.</p>
        </div>

        <div class="bg-slate-900 border border-white/10 rounded-3xl p-6 sm:p-8">
            <form method="POST" action="{{ route('americano.store') }}" id="americano-form"
                  x-data="americanoForm()" class="space-y-6">
                @csrf

                {{-- Tournament Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-white/70 mb-2">Nama Turnamen <span class="text-red-400">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full bg-slate-800 border @error('name') border-red-500 @else border-white/10 @enderror text-white rounded-xl px-4 py-3 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500"
                           placeholder="Contoh: Fun Americano Sunday #1">
                    @error('name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Points per Game --}}
                <div>
                    <label class="block text-sm font-medium text-white/70 mb-2">Target Poin per Game <span class="text-red-400">*</span></label>
                    <div class="grid grid-cols-4 gap-3">
                        @foreach([16, 21, 24, 32] as $pts)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="points_per_game" value="{{ $pts }}" class="peer sr-only" {{ old('points_per_game', 21) == $pts ? 'checked' : '' }}>
                                <div class="py-3 text-center rounded-xl border border-white/10 text-white/60 peer-checked:border-orange-500 peer-checked:bg-orange-500/15 peer-checked:text-orange-400 peer-checked:font-bold transition-all">
                                    {{ $pts }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Player Count Selector --}}
                <div>
                    <label for="playerCount" class="block text-sm font-medium text-white/70 mb-2">Jumlah Pemain (4 - 12) <span class="text-red-400">*</span></label>
                    <div class="flex items-center gap-3 mb-4">
                        <button type="button" @click="if(playerCount > 4) updateCount(playerCount - 1)" class="w-12 h-12 rounded-xl bg-slate-800 border border-white/10 flex items-center justify-center text-white hover:bg-slate-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                        </button>
                        <input type="number" id="playerCount" x-model.number="playerCount" @change="updateCount(playerCount)" min="4" max="12"
                               class="w-20 text-center bg-slate-800 border border-white/10 text-white font-bold rounded-xl px-2 py-3 focus:outline-none focus:border-orange-500">
                        <button type="button" @click="if(playerCount < 12) updateCount(playerCount + 1)" class="w-12 h-12 rounded-xl bg-slate-800 border border-white/10 flex items-center justify-center text-white hover:bg-slate-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>
                    
                    <div class="bg-slate-800/60 border border-orange-500/20 rounded-2xl p-3 text-xs text-orange-300/70 flex items-start gap-2">
                        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <div x-text="`${playerCount} pemain = ${Math.floor(playerCount/4)} lapangan per ronde`"></div>
                            <div x-show="playerCount % 4 !== 0" x-text="`${playerCount % 4} pemain akan istirahat bergiliran tiap ronde`" class="mt-1 text-yellow-400"></div>
                        </div>
                    </div>
                </div>

                {{-- Player Names --}}
                <div>
                    <label class="block text-sm font-medium text-white/70 mb-3">Nama Pemain <span class="text-red-400">*</span></label>
                    @error('players') <p class="mb-2 text-xs text-red-400">{{ $message }}</p> @enderror
                    @error('players.*') <p class="mb-2 text-xs text-red-400">{{ $message }}</p> @enderror

                    <div class="grid sm:grid-cols-2 gap-3">
                        <template x-for="(player, i) in players" :key="i">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-orange-500/20 border border-orange-500/30 flex items-center justify-center text-orange-400 text-xs font-bold shrink-0"
                                     x-text="i + 1"></div>
                                <input :name="`players[${i}]`" type="text" x-model="players[i]"
                                       :placeholder="`Nama Pemain ${i + 1}`"
                                       required
                                       class="flex-1 bg-slate-800 border border-white/10 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                            </div>
                        </template>
                    </div>
                </div>

                <div class="pt-4 border-t border-white/10">
                    <button type="submit"
                            class="w-full py-4 rounded-2xl font-extrabold text-white text-base shadow-lg shadow-orange-500/20 flex items-center justify-center gap-2"
                            style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Mulai Turnamen Americano!
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
function americanoForm() {
    return {
        playerCount: 4,
        players: ['', '', '', ''],
        updateCount(count) {
            let val = parseInt(count);
            if (isNaN(val) || val < 4) val = 4;
            if (val > 12) val = 12;
            this.playerCount = val;
            
            const curr = this.players.length;
            if (val > curr) {
                for (let i = curr; i < val; i++) this.players.push('');
            } else if (val < curr) {
                this.players = this.players.slice(0, val);
            }
        }
    };
}
</script>
</x-app-layout>
