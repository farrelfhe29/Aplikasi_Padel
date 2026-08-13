<x-app-layout>
    <x-slot name="title">Leaderboard — {{ $tournament->name }}</x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="leaderboardExport()">

        {{-- ─── Main Page Content ────────────────────────────────────────── --}}

        {{-- Header --}}
        <div class="text-center mb-10">
            <div class="inline-block bg-orange-500/20 border border-orange-500/30 text-orange-400 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-3">Americano</div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-2">{{ $tournament->name }}</h1>
            <div class="flex items-center justify-center gap-4 text-sm text-white/40">
                <span>{{ $tournament->total_rounds }} ronde</span>
                <span>•</span>
                <span>{{ $tournament->points_per_game }} poin/game</span>
                @if($tournament->status === 'finished')
                    <span>•</span>
                    <span class="text-green-400 font-semibold">Selesai ✓</span>
                @else
                    <span>•</span>
                    <span class="text-orange-400 font-semibold">Ronde {{ $tournament->current_round }}/{{ $tournament->total_rounds }}</span>
                @endif
            </div>
        </div>

        {{-- Podium (top 3) --}}
        @if($leaderboard->count() >= 3)
            <div class="flex items-end justify-center gap-3 mb-10 px-4">
                <div class="flex-1 max-w-28 text-center">
                    <div class="text-3xl mb-2">🥈</div>
                    <div class="text-sm font-bold text-white/80 mb-1 truncate">{{ $leaderboard[1]->name }}</div>
                    <div class="text-xl font-black text-white/70 mb-2">{{ $leaderboard[1]->total_points }}</div>
                    <div class="h-20 bg-slate-700/70 border border-white/10 rounded-t-2xl flex items-end justify-center pb-2">
                        <span class="text-white/40 text-xs font-bold">2</span>
                    </div>
                </div>
                <div class="flex-1 max-w-36 text-center">
                    <div class="text-4xl mb-2">🥇</div>
                    <div class="text-sm font-bold text-yellow-400 mb-1 truncate">{{ $leaderboard[0]->name }}</div>
                    <div class="text-2xl font-black text-yellow-400 mb-2">{{ $leaderboard[0]->total_points }}</div>
                    <div class="h-32 rounded-t-2xl flex items-end justify-center pb-2" style="background: linear-gradient(180deg, rgba(234,179,8,0.3) 0%, rgba(234,179,8,0.1) 100%); border: 1px solid rgba(234,179,8,0.3);">
                        <span class="text-yellow-400/60 text-xs font-bold">1</span>
                    </div>
                </div>
                <div class="flex-1 max-w-28 text-center">
                    <div class="text-3xl mb-2">🥉</div>
                    <div class="text-sm font-bold text-white/80 mb-1 truncate">{{ $leaderboard[2]->name }}</div>
                    <div class="text-xl font-black text-white/70 mb-2">{{ $leaderboard[2]->total_points }}</div>
                    <div class="h-14 bg-slate-700/70 border border-white/10 rounded-t-2xl flex items-end justify-center pb-2">
                        <span class="text-white/40 text-xs font-bold">3</span>
                    </div>
                </div>
            </div>
        @endif

        {{-- Full Leaderboard Table --}}
        <div class="bg-slate-900 border border-white/10 rounded-3xl p-6 mb-8">
            <h2 class="text-lg font-bold text-white mb-4">Klasemen Lengkap</h2>
            <div class="space-y-2">
                @foreach($leaderboard as $idx => $player)
                    <div class="flex items-center gap-4 py-3 {{ $idx > 0 ? 'border-t border-white/5' : '' }}">
                        <div class="w-8 text-center font-black {{ $idx === 0 ? 'text-yellow-400 text-lg' : ($idx === 1 ? 'text-white/60 text-base' : ($idx === 2 ? 'text-amber-600 text-base' : 'text-white/30 text-sm')) }}">
                            {{ $idx + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold {{ $idx === 0 ? 'text-yellow-400' : 'text-white' }}">{{ $player->name }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-xl font-black {{ $idx === 0 ? 'text-yellow-400' : 'text-white' }}">{{ $player->total_points }}</div>
                            <div class="text-xs text-white/30">poin</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Game Results --}}
        <div class="bg-slate-900 border border-white/10 rounded-3xl p-6 mb-8">
            <h2 class="text-lg font-bold text-white mb-4">Hasil Per Ronde</h2>
            @php $currentRound = 0; @endphp
            @foreach($allGames as $game)
                @if($game->round !== $currentRound)
                    @php $currentRound = $game->round; @endphp
                    <div class="text-xs text-orange-400 font-bold uppercase tracking-widest mb-2 {{ $loop->first ? '' : 'mt-5' }}">
                        Ronde {{ $game->round }}
                    </div>
                @endif
                <div class="flex items-center justify-between py-2 border-t border-white/5 text-sm">
                    <div class="text-blue-400 font-medium">{{ $game->playerA1->name }} & {{ $game->playerA2->name }}</div>
                    <div class="text-white font-black mx-3 shrink-0">{{ $game->score_a }} – {{ $game->score_b }}</div>
                    <div class="text-red-400 font-medium text-right">{{ $game->playerB1->name }} & {{ $game->playerB2->name }}</div>
                </div>
            @endforeach
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row gap-3">
            @if($tournament->status === 'finished')
                <button type="button" @click="downloadImage"
                   class="flex-1 bg-green-600 border border-green-500/50 hover:bg-green-500 text-white font-bold py-4 rounded-2xl text-center text-sm transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span x-text="isCapturing ? 'Menyimpan...' : 'Download PNG'"></span>
                </button>
            @endif
            @if($tournament->status === 'ongoing')
                <a href="{{ route('americano.round', [$tournament->id, $tournament->current_round]) }}"
                   class="flex-1 font-bold text-white py-4 rounded-2xl text-center text-sm flex items-center justify-center"
                   style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
                    Lanjut ke Ronde {{ $tournament->current_round }}
                </a>
            @endif
            <a href="{{ route('americano.create') }}"
               class="flex-1 bg-slate-800 border border-white/10 hover:bg-slate-700 text-white font-semibold py-4 rounded-2xl text-center text-sm transition-colors flex items-center justify-center">
                Turnamen Baru
            </a>
            <a href="{{ route('home') }}"
               class="flex-1 bg-slate-800 border border-white/10 hover:bg-slate-700 text-white/70 font-semibold py-4 rounded-2xl text-center text-sm transition-colors flex items-center justify-center">
                Kembali ke Home
            </a>
        </div>
    </div>

    {{-- ─── Hidden Share Card (rendered off-screen, captured for PNG) ───── --}}
    <div id="share-card-wrapper" style="position:fixed; left:-9999px; top:0; pointer-events:none; z-index:-1;">
        <div id="share-card"
             style="width:480px; border-radius:24px; overflow:hidden; font-family:'Inter',sans-serif; padding:0;">

            {{-- Top gradient strip --}}
            <div style="background:linear-gradient(135deg,#ea580c 0%,#f97316 60%,#fb923c 100%); height:6px;"></div>

            <div style="padding:32px 32px 24px;">
                {{-- Branding --}}
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
                    <div style="display:flex; align-items:center;">
                        <div style="width:100px;height:100px;display:flex;align-items:center;justify-content:center;overflow:hidden;">
                            @php
                                $imagePath = public_path('images/Yuk-P.png');
                                $imageData = file_exists($imagePath) ? base64_encode(file_get_contents($imagePath)) : '';
                                $imgSrc = $imageData ? 'data:image/png;base64,' . $imageData : asset('images/Yuk-P.png');
                            @endphp
                            <img src="{{ $imgSrc }}" alt="Padel YUK" style="width:100%;height:100%;object-fit:contain;">
                        </div>
                    </div>
                    <div style="background:rgba(249,115,22,0.15);border:1px solid rgba(249,115,22,0.3);color:#fb923c;font-size:10px;font-weight:700;padding:4px 10px;border-radius:999px;letter-spacing:0.08em;">AMERICANO</div>
                </div>

                {{-- Tournament name --}}
                <div style="text-align:center;margin-bottom:8px;">
                    <div style="font-size:26px;font-weight:900;color:#fff;line-height:1.1;">{{ $tournament->name }}</div>
                    <div style="font-size:12px;color:rgba(255,255,255,0.35);margin-top:6px;letter-spacing:0.02em;">
                        {{ $tournament->total_rounds }} Ronde &nbsp;·&nbsp; {{ $tournament->points_per_game }} Poin/Game
                    </div>
                </div>
            </div>

            {{-- Divider --}}
            <div style="height:1px;background:rgba(255,255,255,0.07);margin:0 32px;"></div>

            {{-- Podium --}}
            @if($leaderboard->count() >= 3)
            <div style="padding:28px 32px 20px;">
                <div style="display:flex;align-items:flex-end;justify-content:center;gap:12px;">
                    {{-- 2nd --}}
                    <div style="flex:1;text-align:center;">
                        <div style="font-size:28px;margin-bottom:6px;">🥈</div>
                        <div style="font-size:13px;font-weight:700;color:rgba(255,255,255,0.8);margin-bottom:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $leaderboard[1]->name }}</div>
                        <div style="font-size:22px;font-weight:900;color:rgba(255,255,255,0.65);margin-bottom:8px;">{{ $leaderboard[1]->total_points }}</div>
                        <div style="height:64px;background:rgba(100,116,139,0.4);border:1px solid rgba(255,255,255,0.08);border-radius:12px 12px 0 0;display:flex;align-items:flex-end;justify-content:center;padding-bottom:6px;">
                            <span style="font-size:11px;font-weight:700;color:rgba(255,255,255,0.3);">2</span>
                        </div>
                    </div>
                    {{-- 1st --}}
                    <div style="flex:1;max-width:140px;text-align:center;">
                        <div style="font-size:36px;margin-bottom:6px;">🥇</div>
                        <div style="font-size:14px;font-weight:700;color:#fbbf24;margin-bottom:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $leaderboard[0]->name }}</div>
                        <div style="font-size:28px;font-weight:900;color:#fbbf24;margin-bottom:8px;">{{ $leaderboard[0]->total_points }}</div>
                        <div style="height:96px;background:linear-gradient(180deg,rgba(234,179,8,0.3) 0%,rgba(234,179,8,0.1) 100%);border:1px solid rgba(234,179,8,0.35);border-radius:12px 12px 0 0;display:flex;align-items:flex-end;justify-content:center;padding-bottom:6px;">
                            <span style="font-size:11px;font-weight:700;color:rgba(234,179,8,0.5);">1</span>
                        </div>
                    </div>
                    {{-- 3rd --}}
                    <div style="flex:1;text-align:center;">
                        <div style="font-size:28px;margin-bottom:6px;">🥉</div>
                        <div style="font-size:13px;font-weight:700;color:rgba(255,255,255,0.8);margin-bottom:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $leaderboard[2]->name }}</div>
                        <div style="font-size:22px;font-weight:900;color:rgba(255,255,255,0.65);margin-bottom:8px;">{{ $leaderboard[2]->total_points }}</div>
                        <div style="height:44px;background:rgba(100,116,139,0.4);border:1px solid rgba(255,255,255,0.08);border-radius:12px 12px 0 0;display:flex;align-items:flex-end;justify-content:center;padding-bottom:6px;">
                            <span style="font-size:11px;font-weight:700;color:rgba(255,255,255,0.3);">3</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Divider --}}
            <div style="height:1px;background:rgba(255,255,255,0.07);margin:0 32px;"></div>

            {{-- Full Rankings --}}
            <div style="padding:20px 32px 24px;">
                <div style="font-size:11px;font-weight:700;color:rgba(255,255,255,0.3);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:12px;">Klasemen Lengkap</div>
                @foreach($leaderboard as $idx => $player)
                    <div style="display:flex;align-items:center;gap:12px;padding:10px 0;{{ $idx > 0 ? 'border-top:1px solid rgba(255,255,255,0.05);' : '' }}">
                        <div style="width:24px;text-align:center;font-weight:900;font-size:{{ $idx === 0 ? '16px' : '13px' }};color:{{ $idx === 0 ? '#fbbf24' : ($idx === 1 ? 'rgba(255,255,255,0.5)' : ($idx === 2 ? '#b45309' : 'rgba(255,255,255,0.25)')) }};">{{ $idx + 1 }}</div>
                        <div style="flex:1;font-weight:700;font-size:14px;color:{{ $idx === 0 ? '#fbbf24' : '#fff' }};">{{ $player->name }}</div>
                        <div style="font-weight:900;font-size:18px;color:{{ $idx === 0 ? '#fbbf24' : '#fff' }};">{{ $player->total_points }}</div>
                        <div style="font-size:10px;color:rgba(255,255,255,0.25);">poin</div>
                    </div>
                @endforeach
            </div>

            {{-- Footer --}}
            <div style="height:1px;background:rgba(255,255,255,0.07);margin:0 32px;"></div>
            <div style="padding:14px 32px;display:flex;align-items:center;justify-content:space-between;">
                <div style="font-size:11px;color:rgba(255,255,255,0.2);">generasisehat.padel</div>
                <div style="font-size:11px;color:rgba(249,115,22,0.6);font-weight:600;">padel americano 🏆</div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('leaderboardExport', () => ({
                isCapturing: false,
                downloadImage() {
                    if (this.isCapturing) return;
                    this.isCapturing = true;

                    const card = document.getElementById('share-card');

                    // Temporarily make visible for html2canvas
                    const wrapper = document.getElementById('share-card-wrapper');
                    wrapper.style.left = '0';
                    wrapper.style.top = '0';
                    wrapper.style.zIndex = '-1';
                    wrapper.style.opacity = '0';

                    html2canvas(card, {
                        backgroundColor: null,   // transparent background
                        scale: 3,                // high-res
                        useCORS: true,
                        logging: false,
                        width: card.offsetWidth,
                        height: card.offsetHeight
                    }).then(canvas => {
                        wrapper.style.left = '-9999px';
                        
                        const filename = `Americano-{{ str_replace(' ', '-', $tournament->name) }}.png`;

                        canvas.toBlob(blob => {
                            if (!blob) {
                                this.fallbackDownload(canvas.toDataURL('image/png'), filename);
                                return;
                            }
                            
                            const file = new File([blob], filename, { type: 'image/png' });
                            
                            if (navigator.canShare && navigator.canShare({ files: [file] })) {
                                navigator.share({
                                    title: 'Hasil Turnamen Padel',
                                    files: [file]
                                }).then(() => {
                                    this.isCapturing = false;
                                }).catch((err) => {
                                    console.log('Share canceled or failed', err);
                                    this.fallbackDownload(canvas.toDataURL('image/png'), filename);
                                });
                            } else {
                                this.fallbackDownload(canvas.toDataURL('image/png'), filename);
                            }
                        }, 'image/png');
                    }).catch(err => {
                        wrapper.style.left = '-9999px';
                        console.error('Export failed', err);
                        alert('Gagal membuat gambar PNG. ' + err.message);
                        this.isCapturing = false;
                    });
                },
                fallbackDownload(dataUrl, filename) {
                    const link = document.createElement('a');
                    link.download = filename;
                    link.href = dataUrl;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    this.isCapturing = false;
                }
            }));
        });
    </script>
    </x-slot>
</x-app-layout>
