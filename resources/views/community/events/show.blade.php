<x-app-layout>
    <x-slot name="title">{{ $event->title }} — Padel YUK</x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Back --}}
        <a href="{{ route('community.events.index') }}" class="inline-flex items-center gap-2 text-white/50 hover:text-white transition-colors text-sm mb-6">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Event
        </a>

        <div class="grid lg:grid-cols-3 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-2">
                {{-- Poster --}}
                @if($event->poster_image)
                    <div class="rounded-3xl overflow-hidden mb-6 border border-white/10">
                        <img src="{{ Storage::url($event->poster_image) }}" alt="{{ $event->title }}" class="w-full h-64 object-cover">
                    </div>
                @endif

                <div class="bg-slate-900 border border-white/10 rounded-3xl p-6 sm:p-8">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white mb-4 leading-snug">{{ $event->title }}</h1>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
                        <div class="bg-slate-800 rounded-2xl p-3 text-center">
                            <div class="text-green-400 text-xs font-semibold uppercase tracking-wider mb-1">Tanggal</div>
                            <div class="text-white font-bold text-sm">{{ $event->date->format('d M Y') }}</div>
                        </div>
                        <div class="bg-slate-800 rounded-2xl p-3 text-center">
                            <div class="text-green-400 text-xs font-semibold uppercase tracking-wider mb-1">Waktu</div>
                            <div class="text-white font-bold text-sm">{{ substr($event->time, 0, 5) }} WIB</div>
                        </div>
                        <div class="bg-slate-800 rounded-2xl p-3 text-center col-span-2 sm:col-span-1">
                            <div class="text-green-400 text-xs font-semibold uppercase tracking-wider mb-1">Peserta</div>
                            <div class="text-white font-bold text-sm">
                                {{ $event->registration_count }}{{ $event->quota > 0 ? '/' . $event->quota : '' }}
                                @if($event->isFull()) <span class="text-red-400 text-xs">Penuh</span> @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-2 text-sm text-white/60 mb-6 bg-slate-800/50 rounded-xl p-3">
                        <svg class="w-4 h-4 text-green-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        <span>{{ $event->location }}</span>
                    </div>

                    @if($event->description)
                        <div class="prose prose-invert prose-sm max-w-none">
                            <h3 class="text-base font-bold text-white mb-3">Deskripsi Event</h3>
                            <div class="text-white/60 leading-relaxed whitespace-pre-wrap">{{ $event->description }}</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-4">
                {{-- Registration Card --}}
                <div class="bg-slate-900 border border-white/10 rounded-3xl p-6 sticky top-20">
                    @if($userRegistration && $userRegistration->status === 'registered')
                        <div class="text-center mb-5">
                            <div class="w-14 h-14 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div class="font-bold text-white mb-1">Anda Terdaftar! ✅</div>
                            <div class="text-white/50 text-sm">Sampai jumpa di event ini</div>
                        </div>
                        <form method="POST" action="{{ route('community.events.cancel', $event) }}">
                            @csrf
                            <button type="submit" class="w-full bg-red-500/10 border border-red-500/30 text-red-400 hover:bg-red-500/20 font-semibold py-2.5 rounded-xl text-sm transition-colors"
                                    onclick="return confirm('Batalkan pendaftaran?')">
                                Batalkan Pendaftaran
                            </button>
                        </form>
                    @elseif($event->isFull())
                        <div class="text-center text-red-400 bg-red-500/10 border border-red-500/20 rounded-2xl p-4">
                            <div class="text-2xl mb-1">😔</div>
                            <div class="font-bold">Kuota Penuh</div>
                            <div class="text-sm text-white/50 mt-1">Event ini sudah penuh</div>
                        </div>
                    @elseif($event->date->isPast())
                        <div class="text-center text-white/30 bg-white/5 rounded-2xl p-4">
                            <div class="font-bold">Event Sudah Lewat</div>
                        </div>
                    @else
                        <div class="text-center mb-5">
                            @if($event->quota > 0)
                                <div class="text-sm text-white/50 mb-2">Sisa Slot</div>
                                <div class="text-3xl font-black text-green-400">{{ $event->quota - $event->registration_count }}</div>
                                <div class="text-xs text-white/30 mt-1">dari {{ $event->quota }} tempat</div>
                                {{-- Progress bar --}}
                                <div class="w-full bg-slate-800 rounded-full h-1.5 mt-3">
                                    <div class="bg-green-500 rounded-full h-1.5 transition-all" style="width: {{ min(($event->registration_count / $event->quota) * 100, 100) }}%"></div>
                                </div>
                            @else
                                <div class="text-sm text-white/50 mb-2">Pendaftaran Terbuka</div>
                                <div class="text-xl font-bold text-green-400">{{ $event->registration_count }} mendaftar</div>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('community.events.register', $event) }}">
                            @csrf
                            <button type="submit" class="w-full btn-primary font-bold text-white py-3 rounded-xl text-sm flex items-center justify-center gap-2 shadow-lg shadow-green-500/20">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                Daftar Ikut
                            </button>
                        </form>
                    @endif

                    {{-- Admin controls --}}
                    @if(auth()->user()->isAdmin())
                        <div class="border-t border-white/10 mt-4 pt-4 flex gap-2">
                            <a href="{{ route('admin.events.edit', $event) }}" class="flex-1 bg-slate-800 hover:bg-slate-700 text-white text-sm font-semibold py-2 rounded-xl text-center transition-colors">Edit</a>
                            <form method="POST" action="{{ route('admin.events.destroy', $event) }}" class="flex-1" onsubmit="return confirm('Hapus event ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full bg-red-500/10 hover:bg-red-500/20 text-red-400 text-sm font-semibold py-2 rounded-xl transition-colors">Hapus</button>
                            </form>
                        </div>
                    @endif
                </div>

                {{-- Registered users --}}
                @if($event->registrations->where('status', 'registered')->count() > 0)
                    <div class="bg-slate-900 border border-white/10 rounded-3xl p-5">
                        <h3 class="text-sm font-bold text-white/70 mb-3">Peserta Terdaftar ({{ $event->registrations->where('status', 'registered')->count() }})</h3>
                        <div class="space-y-2">
                            @foreach($event->registrations->where('status', 'registered')->take(8) as $reg)
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-green-500/20 border border-green-500/30 flex items-center justify-center text-green-400 text-xs font-bold shrink-0">
                                        {{ strtoupper(substr($reg->user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-white/60 text-sm">{{ $reg->user->name }}</span>
                                </div>
                            @endforeach
                            @if($event->registrations->where('status', 'registered')->count() > 8)
                                <div class="text-xs text-white/30">+{{ $event->registrations->where('status', 'registered')->count() - 8 }} lainnya</div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
