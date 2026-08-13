<x-app-layout>
    <x-slot name="title">Event Komunitas — Padel YUK</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-10">
            <div>
                <div class="text-xs text-green-400 font-semibold uppercase tracking-wider mb-1">Komunitas</div>
                <h1 class="text-3xl font-extrabold text-white">Event Mendatang</h1>
                <p class="text-white/50 mt-1">Daftar ikut pertandingan dan event komunitas padel Surabaya</p>
            </div>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.events.create') }}"
                   class="btn-primary inline-flex items-center gap-2 font-bold text-white px-5 py-2.5 rounded-xl self-start sm:self-auto">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Event
                </a>
            @endif
        </div>

        {{-- Event Grid --}}
        @if($events->count())
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                @foreach($events as $event)
                    <a href="{{ route('community.events.show', $event) }}" class="group block">
                        <div class="bg-slate-900 border border-white/10 rounded-3xl overflow-hidden card-hover h-full flex flex-col">
                            {{-- Poster / placeholder --}}
                            <div class="relative h-44 bg-gradient-to-br from-green-900/50 to-slate-800 overflow-hidden">
                                @if($event->poster_image)
                                    <img src="{{ Storage::url($event->poster_image) }}" alt="{{ $event->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-16 h-16 text-green-500/30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                                {{-- Quota badge --}}
                                <div class="absolute top-3 right-3 bg-slate-900/80 backdrop-blur-sm border border-white/10 text-white text-xs font-semibold px-2.5 py-1 rounded-full">
                                    @if($event->quota > 0)
                                        {{ $event->registration_count }}/{{ $event->quota }} peserta
                                    @else
                                        Terbuka
                                    @endif
                                </div>
                            </div>

                            <div class="p-5 flex flex-col flex-1">
                                <div class="flex items-center gap-2 text-xs text-green-400 font-semibold mb-2">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $event->date->translatedFormat('l, d F Y') }}
                                    <span class="text-white/30">•</span>
                                    <span class="text-white/60">{{ substr($event->time, 0, 5) }} WIB</span>
                                </div>
                                <h3 class="text-lg font-bold text-white mb-2 group-hover:text-green-400 transition-colors leading-snug">
                                    {{ $event->title }}
                                </h3>
                                <p class="text-white/50 text-sm leading-relaxed flex-1 line-clamp-2">{{ $event->description }}</p>
                                <div class="flex items-center gap-1.5 mt-4 text-xs text-white/40">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $event->location }}
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{ $events->links() }}
        @else
            <div class="text-center py-20 text-white/30">
                <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="text-lg font-medium">Belum ada event mendatang</p>
                <p class="text-sm mt-1">Pantau terus halaman ini untuk update event terbaru!</p>
            </div>
        @endif

        {{-- Past Events --}}
        @if($pastEvents->count())
            <div class="border-t border-white/10 pt-8">
                <h2 class="text-lg font-bold text-white/50 mb-4">Event Lalu</h2>
                <div class="grid sm:grid-cols-3 gap-4">
                    @foreach($pastEvents as $event)
                        <a href="{{ route('community.events.show', $event) }}" class="bg-slate-900/50 border border-white/5 rounded-2xl p-4 hover:bg-slate-900 transition-colors">
                            <div class="text-xs text-white/30 mb-1">{{ $event->date->format('d M Y') }}</div>
                            <div class="font-semibold text-white/60 text-sm line-clamp-1">{{ $event->title }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
