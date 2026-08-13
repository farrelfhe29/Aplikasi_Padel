<x-app-layout>
    <x-slot name="title">Ronde {{ $round }} — {{ $tournament->name }}</x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="bg-orange-500/20 border border-orange-500/30 text-orange-400 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Americano</span>
                    <span class="text-white/30 text-xs">{{ $tournament->name }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white">Ronde {{ $round }} dari {{ $tournament->total_rounds }}</h1>
                <p class="text-white/50 text-sm mt-1">Target: <span class="text-orange-400 font-bold">{{ $tournament->points_per_game }} poin</span> per game</p>
            </div>
            <a href="{{ route('americano.leaderboard', $tournament->id) }}" class="inline-flex items-center gap-2 bg-slate-800 border border-white/10 hover:bg-slate-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors self-start">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Leaderboard
            </a>
        </div>

        {{-- Round progress --}}
        <div class="flex gap-1.5 mb-8">
            @for($r = 1; $r <= $tournament->total_rounds; $r++)
                <div class="flex-1 h-1.5 rounded-full {{ $r < $round ? 'bg-orange-500' : ($r === $round ? 'bg-orange-400 animate-pulse' : 'bg-white/10') }}"></div>
            @endfor
        </div>

        {{-- Game Cards --}}
        <div class="space-y-4">
            @foreach($games as $game)
                <div class="bg-slate-900 border {{ $game->status === 'finished' ? 'border-green-500/20 bg-green-500/5' : 'border-white/10' }} rounded-3xl p-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-slate-700 flex items-center justify-center text-white/50 text-xs font-bold">{{ $game->court }}</div>
                            <span class="text-white/40 text-xs uppercase tracking-wider">Lapangan {{ $game->court }}</span>
                        </div>
                        @if($game->status === 'finished')
                            <div class="flex items-center gap-1.5 text-green-400 text-xs font-semibold">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Selesai
                            </div>
                        @elseif($game->status === 'ongoing')
                            <div class="flex items-center gap-1.5 text-orange-400 text-xs font-semibold">
                                <div class="w-2 h-2 rounded-full bg-orange-400 animate-pulse"></div>
                                Sedang Bermain
                            </div>
                        @else
                            <div class="text-white/30 text-xs">Menunggu</div>
                        @endif
                    </div>

                    {{-- Teams --}}
                    <div class="grid grid-cols-3 items-center gap-4">
                        {{-- Team A --}}
                        <div class="text-center">
                            <div class="text-sm font-bold text-blue-400">{{ $game->playerA1->name }}</div>
                            <div class="text-xs text-white/40 mt-0.5">&</div>
                            <div class="text-sm font-bold text-blue-400">{{ $game->playerA2->name }}</div>
                        </div>

                        {{-- Score --}}
                        <div class="text-center">
                            @if($game->status === 'finished')
                                <div class="text-3xl font-black text-white">{{ $game->score_a }} <span class="text-white/30">–</span> {{ $game->score_b }}</div>
                            @elseif($game->status === 'ongoing')
                                <a href="{{ route('americano.scoreboard', [$tournament->id, $game->id]) }}"
                                   class="inline-block py-2.5 px-5 rounded-2xl font-extrabold text-white text-sm"
                                   style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
                                    Buka<br>Scoreboard
                                </a>
                            @else
                                <div class="text-white/30 text-sm">VS</div>
                            @endif
                        </div>

                        {{-- Team B --}}
                        <div class="text-center">
                            <div class="text-sm font-bold text-red-400">{{ $game->playerB1->name }}</div>
                            <div class="text-xs text-white/40 mt-0.5">&</div>
                            <div class="text-sm font-bold text-red-400">{{ $game->playerB2->name }}</div>
                        </div>
                    </div>

                    {{-- Finish manually if ongoing --}}
                    @if($game->status === 'ongoing')
                        <div class="mt-4 border-t border-white/5 pt-4 flex justify-end">
                            <form method="POST" action="{{ route('americano.finishGame', [$tournament->id, $game->id]) }}" onsubmit="return confirm('Selesaikan game ini? Pastikan skor sudah benar di scoreboard.')">
                                @csrf
                                <button type="submit" class="text-xs text-orange-400 hover:text-orange-300 font-semibold transition-colors">
                                    Tandai Selesai →
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Check if all games finished --}}
        @if($games->every(fn($g) => $g->status === 'finished'))
            <div class="mt-6 bg-green-500/10 border border-green-500/20 rounded-3xl p-6 text-center">
                <div class="text-4xl mb-3">🎉</div>
                <div class="font-bold text-white text-lg mb-1">Semua game di Ronde {{ $round }} selesai!</div>
                @if($round < $tournament->total_rounds)
                    <p class="text-white/50 text-sm mb-4">Lanjutkan ke ronde berikutnya untuk rotasi pasangan baru.</p>
                    <form method="POST" action="{{ route('americano.advanceRound', [$tournament->id, $round]) }}">
                        @csrf
                        <button type="submit" class="inline-block font-bold text-white px-8 py-3 rounded-2xl text-sm transition-transform hover:scale-105"
                                style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
                            Lanjut ke Ronde {{ $round + 1 }} →
                        </button>
                    </form>
                @else
                    <p class="text-white/50 text-sm mb-4">Semua ronde selesai! Lihat siapa pemenang turnamen ini.</p>
                    <form method="POST" action="{{ route('americano.advanceRound', [$tournament->id, $round]) }}">
                        @csrf
                        <button type="submit" class="inline-block font-bold text-white px-8 py-3 rounded-2xl text-sm transition-transform hover:scale-105"
                                style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
                            Lihat Leaderboard 🏆
                        </button>
                    </form>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
