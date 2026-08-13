<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Scoreboard — {{ implode(' & ', $match->teamA->pluck('name')->toArray()) }} vs {{ implode(' & ', $match->teamB->pluck('name')->toArray()) }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; touch-action: manipulation; }
        .score-btn { min-height: 90px; transition: all 0.15s ease; user-select: none; -webkit-tap-highlight-color: transparent; }
        .score-btn:active { transform: scale(0.95); }
        .score-display { font-variant-numeric: tabular-nums; }
        .pulse-win { animation: pulseWin 0.6s ease-out; }
        @keyframes pulseWin { 0% { transform: scale(1); } 50% { transform: scale(1.05); background: rgba(22,163,74,0.2); } 100% { transform: scale(1); } }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-white select-none"
      x-data="scoreboard()"
      x-init="init()"
      @keydown.escape.window="showConfirmEnd = false">

    {{-- ─── Top bar ─────────────────────────────────────────────────────── --}}
    <div class="flex items-center justify-between px-4 py-3 bg-slate-900/80 backdrop-blur border-b border-white/10 sticky top-0 z-50">
        <a href="{{ route('home') }}" class="flex items-center gap-1.5 text-sm font-semibold text-white/50 hover:text-white transition-colors p-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span class="hidden sm:inline">Kembali</span>
        </a>
        <div class="text-center">
            <div class="text-xs text-white/40 uppercase tracking-wider">
                {{ $match->mode === 'double' ? 'Double' : 'Single' }} •
                {{ $match->format === 'best_of_3' ? 'Best of 3' : 'Best of 1' }}
                <span x-show="state.golden_point" class="text-yellow-400"> • Golden Point</span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            {{-- Undo button --}}
            <button @click="undo()" :disabled="state.history?.length === 0 || loading"
                    class="p-2 rounded-lg text-white/50 hover:text-amber-400 hover:bg-amber-500/10 disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
                    title="Undo">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            </button>
            <button @click="showConfirmEnd = true"
                    class="p-2 rounded-lg text-white/50 hover:text-red-400 hover:bg-red-500/10 transition-colors"
                    title="Akhiri Match">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
            </button>
        </div>
    </div>

    {{-- ─── Main Scoreboard ─────────────────────────────────────────────── --}}
    <div class="flex flex-col min-h-[calc(100vh-57px)]">

        {{-- Set scores --}}
        <div class="bg-slate-900/60 border-b border-white/5 px-4 py-3">
            <div class="max-w-lg mx-auto flex items-center justify-between">
                <div class="text-sm font-bold text-blue-400">{{ implode(' / ', $match->teamA->pluck('name')->toArray()) }}</div>
                <div class="flex items-center gap-4">
                    {{-- Set history --}}
                    <div class="flex gap-2" x-show="state.set_history?.length > 0">
                        <template x-for="(set, i) in state.set_history" :key="i">
                            <div class="text-center text-xs">
                                <div class="text-white/30 mb-0.5" x-text="'S' + (i+1)"></div>
                                <div class="font-bold" :class="set.winner === 'A' ? 'text-blue-400' : 'text-red-400'" x-text="set.games_a + '-' + set.games_b"></div>
                            </div>
                        </template>
                    </div>
                    {{-- Set score big --}}
                    <div class="bg-slate-800 border border-white/10 rounded-2xl px-5 py-2 flex items-center gap-3">
                        <span class="text-2xl font-black score-display" :class="state.sets_a > state.sets_b ? 'text-blue-400' : 'text-white/70'" x-text="state.sets_a ?? 0"></span>
                        <span class="text-white/30 font-bold">:</span>
                        <span class="text-2xl font-black score-display" :class="state.sets_b > state.sets_a ? 'text-red-400' : 'text-white/70'" x-text="state.sets_b ?? 0"></span>
                    </div>
                </div>
                <div class="text-sm font-bold text-red-400 text-right">{{ implode(' / ', $match->teamB->pluck('name')->toArray()) }}</div>
            </div>
        </div>

        {{-- Game score & Point score --}}
        <div class="flex-1 flex flex-col items-center justify-center px-4 py-6 gap-6">

            {{-- Status label (Deuce / Advantage / Set info) --}}
            <div class="h-8 flex items-center">
                <div x-show="state.deuce && !state.in_tiebreak" x-cloak
                     class="bg-yellow-500/20 border border-yellow-500/40 text-yellow-400 font-black text-sm px-6 py-1.5 rounded-full uppercase tracking-wider animate-pulse">
                    DEUCE
                </div>
                <div x-show="state.advantage === 'A'" x-cloak
                     class="bg-blue-500/20 border border-blue-500/40 text-blue-400 font-black text-sm px-6 py-1.5 rounded-full uppercase tracking-wider">
                    Advantage Tim A
                </div>
                <div x-show="state.advantage === 'B'" x-cloak
                     class="bg-red-500/20 border border-red-500/40 text-red-400 font-black text-sm px-6 py-1.5 rounded-full uppercase tracking-wider">
                    Advantage Tim B
                </div>
                <div x-show="state.in_tiebreak" x-cloak
                     class="bg-purple-500/20 border border-purple-500/40 text-purple-400 font-black text-sm px-6 py-1.5 rounded-full uppercase tracking-wider">
                    TIEBREAK
                </div>
                <div x-show="!state.deuce && !state.advantage && !state.in_tiebreak" x-cloak
                     class="text-white/20 text-sm">
                    Set <span x-text="state.current_set ?? 1"></span>
                    <span x-show="state.format === 'best_of_3'" x-cloak>
                        • Menang <span x-text="state.sets_to_win"></span> Set
                    </span>
                </div>
            </div>

            {{-- Point Score Card --}}
            <div class="w-full max-w-sm bg-gradient-to-br from-slate-900 to-slate-800 border border-white/10 rounded-3xl p-6">
                {{-- Current GAME points --}}
                <div class="flex items-center justify-center gap-8 mb-6">
                    <div class="text-center">
                        <div class="text-xs text-blue-400 font-semibold mb-2 uppercase tracking-widest">Tim A</div>
                        <div class="score-display font-black leading-none" style="font-size: 4rem"
                             :class="state.advantage === 'A' ? 'text-blue-300' : 'text-white'"
                             x-text="getPointLabel('A')">0</div>
                    </div>
                    <div class="text-3xl text-white/20 font-black">:</div>
                    <div class="text-center">
                        <div class="text-xs text-red-400 font-semibold mb-2 uppercase tracking-widest">Tim B</div>
                        <div class="score-display font-black leading-none" style="font-size: 4rem"
                             :class="state.advantage === 'B' ? 'text-red-300' : 'text-white'"
                             x-text="getPointLabel('B')">0</div>
                    </div>
                </div>

                {{-- GAMES score --}}
                <div class="border-t border-white/10 pt-4">
                    <div class="text-xs text-center text-white/30 mb-3 uppercase tracking-widest">Games di Set ini</div>
                    <div class="flex items-center justify-center gap-6">
                        <div class="text-4xl font-black score-display" :class="state.games_a > state.games_b ? 'text-blue-400' : 'text-white/60'"
                             x-text="state.in_tiebreak ? state.tb_points_a : (state.games_a ?? 0)">0</div>
                        <div class="text-white/20 font-bold">–</div>
                        <div class="text-4xl font-black score-display" :class="state.games_b > state.games_a ? 'text-red-400' : 'text-white/60'"
                             x-text="state.in_tiebreak ? state.tb_points_b : (state.games_b ?? 0)">0</div>
                    </div>
                    <div x-show="state.in_tiebreak" x-cloak class="text-center text-xs text-purple-400 mt-2">First to 7, unggul 2</div>
                </div>
            </div>

            {{-- Point Buttons --}}
            <div class="grid grid-cols-2 gap-4 w-full max-w-sm">
                <button id="btn-team-a"
                        @click="addPoint('A')"
                        :disabled="loading || state.status === 'finished'"
                        class="score-btn bg-gradient-to-br from-blue-600 to-blue-800 border-2 border-blue-500/50 rounded-3xl flex flex-col items-center justify-center gap-2 shadow-xl shadow-blue-900/50 disabled:opacity-50 disabled:cursor-not-allowed active:shadow-none">
                    <div class="text-xs font-semibold text-blue-200 uppercase tracking-wider">Tim A</div>
                    <div class="text-3xl font-black text-white">+1</div>
                    <div class="text-xs text-blue-300 font-medium">POINT</div>
                </button>
                <button id="btn-team-b"
                        @click="addPoint('B')"
                        :disabled="loading || state.status === 'finished'"
                        class="score-btn bg-gradient-to-br from-red-600 to-red-800 border-2 border-red-500/50 rounded-3xl flex flex-col items-center justify-center gap-2 shadow-xl shadow-red-900/50 disabled:opacity-50 disabled:cursor-not-allowed active:shadow-none">
                    <div class="text-xs font-semibold text-red-200 uppercase tracking-wider">Tim B</div>
                    <div class="text-3xl font-black text-white">+1</div>
                    <div class="text-xs text-red-300 font-medium">POINT</div>
                </button>
            </div>

            {{-- Loading indicator --}}
            <div x-show="loading" x-cloak class="text-white/40 text-sm flex items-center gap-2">
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                Memproses...
            </div>
        </div>
    </div>

    {{-- ─── Match Won Overlay ───────────────────────────────────────────── --}}
    <div x-show="state.status === 'finished'" x-cloak
         class="fixed inset-0 bg-slate-950/90 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 border border-white/10 rounded-3xl p-8 max-w-sm w-full text-center shadow-2xl">
            <div class="text-6xl mb-4">🏆</div>
            <div class="text-xl text-white/50 mb-2">Match Selesai!</div>
            <div class="text-3xl font-black text-white mb-2">
                <span :class="state.winner === 'A' ? 'text-blue-400' : 'text-red-400'"
                      x-text="state.winner === 'A' ? '{{ implode(' & ', $match->teamA->pluck('name')->toArray()) }}' : '{{ implode(' & ', $match->teamB->pluck('name')->toArray()) }}'"></span>
            </div>
            <div class="text-white/50 mb-6">Memenangkan pertandingan!</div>
            <div class="flex gap-3">
                <a href="{{ route('match.result', $match->id) }}"
                   class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl transition-colors">
                    Lihat Hasil
                </a>
                <a href="{{ route('match.create') }}"
                   class="flex-1 bg-slate-700 hover:bg-slate-600 text-white font-semibold py-3 rounded-xl transition-colors">
                    Match Baru
                </a>
            </div>
        </div>
    </div>

    {{-- ─── Confirm End Modal ───────────────────────────────────────────── --}}
    <div x-show="showConfirmEnd" x-cloak
         class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-slate-900 border border-white/10 rounded-3xl p-6 max-w-sm w-full text-center">
            <div class="text-4xl mb-4">⚠️</div>
            <h3 class="text-lg font-bold text-white mb-2">Akhiri Match?</h3>
            <p class="text-white/50 text-sm mb-6">Match akan diakhiri dan Anda akan diarahkan ke halaman hasil.</p>
            <div class="flex gap-3">
                <button @click="showConfirmEnd = false" class="flex-1 bg-slate-700 hover:bg-slate-600 text-white font-semibold py-3 rounded-xl transition-colors">Batal</button>
                <form method="POST" action="{{ route('match.finish', $match->id) }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition-colors">Akhiri</button>
                </form>
            </div>
        </div>
    </div>

<script>
function scoreboard() {
    return {
        matchId: {{ $match->id }},
        state: @json($state),
        loading: false,
        showConfirmEnd: false,
        csrfToken: document.querySelector('meta[name="csrf-token"]').content,

        init() {
            // If match already finished on load
            if (this.state.status === 'finished') {
                window.location.href = '{{ route('match.result', $match->id) }}';
            }
        },

        getPointLabel(team) {
            if (this.state.in_tiebreak) {
                return team === 'A' ? (this.state.tb_points_a ?? 0) : (this.state.tb_points_b ?? 0);
            }
            if (this.state.deuce) return 'Deuce';
            if (this.state.advantage === team) return 'Adv';
            if (this.state.advantage && this.state.advantage !== team) return '40';
            const pts = team === 'A' ? (this.state.points_a ?? 0) : (this.state.points_b ?? 0);
            return ['0', '15', '30', '40'][pts] ?? '0';
        },

        async addPoint(team) {
            if (this.loading || this.state.status === 'finished') return;
            this.loading = true;
            try {
                const res = await fetch(`/match/${this.matchId}/add-point`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ team }),
                });
                if (!res.ok) throw new Error('Server error');
                this.state = await res.json();

                // Haptic feedback
                if (navigator.vibrate) navigator.vibrate(30);
            } catch(e) {
                console.error(e);
            } finally {
                this.loading = false;
            }
        },

        async undo() {
            if (this.loading) return;
            this.loading = true;
            try {
                const res = await fetch(`/match/${this.matchId}/undo`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' },
                });
                if (!res.ok) throw new Error('Cannot undo');
                this.state = await res.json();
            } catch(e) { console.error(e); }
            finally { this.loading = false; }
        },
    };
}
</script>
</body>
</html>
