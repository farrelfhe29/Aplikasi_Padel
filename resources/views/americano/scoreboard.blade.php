<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Americano — Lapangan {{ $game->court }} Ronde {{ $game->round }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; touch-action: manipulation; }
        .score-btn { min-height: 100px; transition: all 0.12s ease; user-select: none; -webkit-tap-highlight-color: transparent; }
        .score-btn:active { transform: scale(0.95); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-white select-none"
      x-data="americanoScoreboard()"
      x-init="init()">

    {{-- Top Bar --}}
    <div class="flex items-center justify-between px-4 py-3 bg-slate-900/80 backdrop-blur border-b border-white/10 sticky top-0 z-50">
        <a href="{{ route('americano.round', [$tournament->id, $game->round]) }}" class="text-white/50 hover:text-white transition-colors p-1">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div class="text-center">
            <div class="text-xs text-orange-400 font-semibold uppercase tracking-widest">{{ $tournament->name }}</div>
            <div class="text-xs text-white/40">Ronde {{ $game->round }} / Lapangan {{ $game->court }}</div>
        </div>
        <div class="w-8"></div>
    </div>

    {{-- Players & Score --}}
    <div class="flex flex-col items-center justify-center min-h-[calc(100vh-57px)] px-4 py-8 gap-6">

        {{-- Progress Bar --}}
        <div class="w-full max-w-sm">
            <div class="flex justify-between text-xs text-white/40 mb-1.5">
                <span x-text="(scoreA + scoreB) + ' / {{ $tournament->points_per_game }} poin'"></span>
                <span x-text="Math.round(((scoreA + scoreB) / {{ $tournament->points_per_game }}) * 100) + '%'"></span>
            </div>
            <div class="w-full bg-slate-800 rounded-full h-2">
                <div class="bg-orange-500 rounded-full h-2 transition-all duration-300"
                     :style="`width: ${Math.min(((scoreA + scoreB) / {{ $tournament->points_per_game }}) * 100, 100)}%`"></div>
            </div>
        </div>

        {{-- Score Card --}}
        <div class="w-full max-w-sm bg-gradient-to-br from-slate-900 to-slate-800 border border-white/10 rounded-3xl p-6 text-center">
            <div class="grid grid-cols-3 items-center gap-4">
                <div>
                    <div class="text-xs text-blue-400 font-semibold uppercase tracking-widest mb-2">Tim A</div>
                    <div class="text-xs text-white/60 mb-3 leading-relaxed">
                        {{ $game->playerA1->name }}<br>
                        <span class="text-white/30">&</span><br>
                        {{ $game->playerA2->name }}
                    </div>
                    <div class="text-6xl font-black text-white" x-text="scoreA">{{ $game->score_a }}</div>
                </div>
                <div class="text-white/20 text-3xl font-black">:</div>
                <div>
                    <div class="text-xs text-red-400 font-semibold uppercase tracking-widest mb-2">Tim B</div>
                    <div class="text-xs text-white/60 mb-3 leading-relaxed">
                        {{ $game->playerB1->name }}<br>
                        <span class="text-white/30">&</span><br>
                        {{ $game->playerB2->name }}
                    </div>
                    <div class="text-6xl font-black text-white" x-text="scoreB">{{ $game->score_b }}</div>
                </div>
            </div>
        </div>

        {{-- Point Buttons --}}
        <div class="grid grid-cols-2 gap-4 w-full max-w-sm" x-show="status !== 'finished'" x-cloak>
            <button @click="addPoint('A')" :disabled="loading || status === 'finished'"
                    class="score-btn bg-gradient-to-br from-blue-600 to-blue-800 border-2 border-blue-500/50 rounded-3xl flex flex-col items-center justify-center gap-2 shadow-xl shadow-blue-900/50 disabled:opacity-50">
                <div class="text-xs font-semibold text-blue-200 uppercase tracking-wider">Tim A</div>
                <div class="text-4xl font-black text-white">+1</div>
                <div class="text-xs text-blue-300">POINT</div>
            </button>
            <button @click="addPoint('B')" :disabled="loading || status === 'finished'"
                    class="score-btn bg-gradient-to-br from-red-600 to-red-800 border-2 border-red-500/50 rounded-3xl flex flex-col items-center justify-center gap-2 shadow-xl shadow-red-900/50 disabled:opacity-50">
                <div class="text-xs font-semibold text-red-200 uppercase tracking-wider">Tim B</div>
                <div class="text-4xl font-black text-white">+1</div>
                <div class="text-xs text-red-300">POINT</div>
            </button>
        </div>

        <div x-show="loading" x-cloak class="text-white/40 text-sm flex items-center gap-2">
            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
            Memproses...
        </div>
    </div>

    {{-- Game Finished Overlay --}}
    <div x-show="status === 'finished'" x-cloak
         class="fixed inset-0 bg-slate-950/90 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 border border-orange-500/20 rounded-3xl p-8 max-w-sm w-full text-center shadow-2xl">
            <div class="text-6xl mb-4">🎾</div>
            <div class="text-xl text-white/50 mb-2">Game Selesai!</div>
            <div class="text-4xl font-black mb-1">
                <span :class="scoreA > scoreB ? 'text-blue-400' : 'text-white/60'" x-text="scoreA"></span>
                <span class="text-white/30 mx-2">–</span>
                <span :class="scoreB > scoreA ? 'text-red-400' : 'text-white/60'" x-text="scoreB"></span>
            </div>
            <div class="text-white/40 text-sm mb-6">
                <template x-if="scoreA > scoreB">
                    <span class="text-blue-400 font-bold">{{ $game->playerA1->name }} & {{ $game->playerA2->name }}</span>
                </template>
                <template x-if="scoreB > scoreA">
                    <span class="text-red-400 font-bold">{{ $game->playerB1->name }} & {{ $game->playerB2->name }}</span>
                </template>
                <template x-if="scoreA === scoreB">
                    <span>Seri!</span>
                </template>
                <span> menang</span>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('americano.round', [$tournament->id, $game->round]) }}"
                   class="flex-1 font-bold text-white py-3 rounded-xl text-sm"
                   style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
                    Kembali ke Ronde
                </a>
                <a href="{{ route('americano.leaderboard', $tournament->id) }}"
                   class="flex-1 bg-slate-700 hover:bg-slate-600 text-white font-semibold py-3 rounded-xl text-sm transition-colors">
                    Leaderboard
                </a>
            </div>
        </div>
    </div>

<script>
function americanoScoreboard() {
    return {
        tournamentId: {{ $tournament->id }},
        gameId: {{ $game->id }},
        maxPoints: {{ $tournament->points_per_game }},
        scoreA: {{ $game->score_a }},
        scoreB: {{ $game->score_b }},
        status: '{{ $game->status }}',
        loading: false,
        csrfToken: document.querySelector('meta[name="csrf-token"]').content,

        init() {
            if (this.status === 'finished') {
                // already finished, show overlay
            }
        },

        async addPoint(team) {
            if (this.loading || this.status === 'finished') return;
            this.loading = true;
            try {
                const res = await fetch(`/americano/${this.tournamentId}/game/${this.gameId}/add-point`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ team }),
                });
                if (!res.ok) throw new Error('Server error');
                const data = await res.json();
                this.scoreA = data.score_a;
                this.scoreB = data.score_b;
                this.status = data.status;
                if (navigator.vibrate) navigator.vibrate(30);
            } catch(e) {
                console.error(e);
            } finally {
                this.loading = false;
            }
        },
    };
}
</script>
</body>
</html>
