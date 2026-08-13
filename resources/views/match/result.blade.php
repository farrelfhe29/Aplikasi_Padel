<x-app-layout>
    <x-slot name="title">Hasil Match — Padel YUK</x-slot>

    <div class="max-w-2xl mx-auto px-4 py-12">
        @php
            $state = $state ?? [];
            $winner = $state['winner'] ?? $match->winner_side ?? null;
            $teamANames = $match->teamA->pluck('name')->implode(' & ');
            $teamBNames = $match->teamB->pluck('name')->implode(' & ');
            $winnerName = $winner === 'A' ? $teamANames : ($winner === 'B' ? $teamBNames : 'Tidak ditentukan');
            $setHistory = $state['set_history'] ?? [];
        @endphp

        {{-- Winner Card --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-green-900/30 to-slate-900 border border-green-500/30 rounded-3xl p-8 mb-8 text-center">
            <div class="absolute inset-0" style="background: radial-gradient(ellipse at center top, rgba(22,163,74,0.15) 0%, transparent 70%);"></div>
            <div class="relative">
                <div class="text-6xl mb-4">🏆</div>
                <div class="text-white/50 text-sm mb-2 uppercase tracking-widest font-semibold">Pemenang Match</div>
                <div class="text-3xl sm:text-4xl font-extrabold text-white mb-2">{{ $winnerName }}</div>
                <div class="inline-block bg-green-500/20 border border-green-500/40 text-green-400 text-sm font-semibold px-4 py-1.5 rounded-full">
                    Selamat! 🎉
                </div>
            </div>
        </div>

        {{-- Score Summary --}}
        <div class="bg-slate-900 border border-white/10 rounded-3xl p-6 mb-6">
            <h2 class="text-lg font-bold text-white mb-5 text-center">Ringkasan Skor</h2>

            {{-- Teams Header --}}
            <div class="grid grid-cols-3 gap-4 mb-4 text-center">
                <div class="font-bold text-blue-400 text-sm">{{ $teamANames }}</div>
                <div class="text-white/30 text-xs font-semibold uppercase tracking-wider self-center">Set</div>
                <div class="font-bold text-red-400 text-sm">{{ $teamBNames }}</div>
            </div>

            {{-- Set Scores --}}
            @forelse($setHistory as $set)
                <div class="grid grid-cols-3 gap-4 text-center py-3 border-t border-white/5">
                    <div class="text-2xl font-black {{ $set['winner'] === 'A' ? 'text-blue-400' : 'text-white/40' }}">
                        {{ $set['games_a'] }}
                    </div>
                    <div class="text-white/30 text-xs self-center">
                        Set {{ $set['set'] }}
                        @if($set['tiebreak'])<span class="text-purple-400"> TB</span>@endif
                    </div>
                    <div class="text-2xl font-black {{ $set['winner'] === 'B' ? 'text-red-400' : 'text-white/40' }}">
                        {{ $set['games_b'] }}
                    </div>
                </div>
            @empty
                @if($match->sets->count())
                    @foreach($match->sets as $set)
                        <div class="grid grid-cols-3 gap-4 text-center py-3 border-t border-white/5">
                            <div class="text-2xl font-black {{ $set->winner_side === 'A' ? 'text-blue-400' : 'text-white/40' }}">{{ $set->games_a }}</div>
                            <div class="text-white/30 text-xs self-center">Set {{ $set->set_number }}@if($set->is_tiebreak) <span class="text-purple-400">TB</span>@endif</div>
                            <div class="text-2xl font-black {{ $set->winner_side === 'B' ? 'text-red-400' : 'text-white/40' }}">{{ $set->games_b }}</div>
                        </div>
                    @endforeach
                @else
                    <p class="text-white/30 text-center py-4 text-sm">Data set tidak tersedia</p>
                @endif
            @endforelse

            {{-- Final Set score --}}
            <div class="grid grid-cols-3 gap-4 text-center py-4 border-t border-white/20 mt-2">
                <div class="text-3xl font-black {{ ($state['sets_a'] ?? 0) > ($state['sets_b'] ?? 0) ? 'text-blue-400' : 'text-white/60' }}">
                    {{ $state['sets_a'] ?? $match->sets->where('winner_side', 'A')->count() }}
                </div>
                <div class="text-white/40 text-xs font-semibold uppercase tracking-wider self-center">Set Victs</div>
                <div class="text-3xl font-black {{ ($state['sets_b'] ?? 0) > ($state['sets_a'] ?? 0) ? 'text-red-400' : 'text-white/60' }}">
                    {{ $state['sets_b'] ?? $match->sets->where('winner_side', 'B')->count() }}
                </div>
            </div>
        </div>

        {{-- Match Info --}}
        <div class="bg-slate-900 border border-white/10 rounded-2xl p-5 mb-8 grid grid-cols-3 gap-4 text-center">
            <div>
                <div class="text-white/40 text-xs mb-1">Mode</div>
                <div class="text-white font-semibold text-sm">{{ $match->mode === 'double' ? 'Double' : 'Single' }}</div>
            </div>
            <div>
                <div class="text-white/40 text-xs mb-1">Format</div>
                <div class="text-white font-semibold text-sm">{{ $match->format === 'best_of_3' ? 'Bo3' : 'Bo1' }}</div>
            </div>
            <div>
                <div class="text-white/40 text-xs mb-1">Golden Point</div>
                <div class="text-white font-semibold text-sm">{{ $match->golden_point ? 'Ya' : 'Tidak' }}</div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('match.create') }}"
               class="flex-1 btn-primary font-bold text-white py-4 rounded-2xl text-center flex items-center justify-center gap-2 shadow-lg shadow-green-500/20">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Match Baru
            </a>
            <a href="{{ route('home') }}"
               class="flex-1 bg-slate-800 border border-white/10 hover:bg-slate-700 text-white font-semibold py-4 rounded-2xl text-center transition-colors">
                Kembali ke Home
            </a>
        </div>
    </div>
</x-app-layout>
