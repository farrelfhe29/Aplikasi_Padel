<x-app-layout>
    <x-slot name="title">Kelola Event — Admin</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <div class="text-xs text-amber-400 font-semibold uppercase tracking-wider mb-1">Admin Panel</div>
                <h1 class="text-3xl font-extrabold text-white">Kelola Event</h1>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.members.index') }}" class="inline-flex items-center gap-2 bg-slate-800 border border-white/10 hover:bg-slate-700 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition-colors">
                    Kelola Member
                </a>
                <a href="{{ route('admin.events.create') }}" class="btn-primary inline-flex items-center gap-2 font-bold text-white px-5 py-2.5 rounded-xl text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Event
                </a>
            </div>
        </div>

        {{-- Event List --}}
        <div class="bg-slate-900 border border-white/10 rounded-3xl p-6">
            @if($events->count())
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-xs text-white/40 uppercase tracking-wider border-b border-white/10">
                                <th class="text-left pb-3">Event</th>
                                <th class="text-left pb-3">Tanggal & Waktu</th>
                                <th class="text-left pb-3">Lokasi</th>
                                <th class="text-left pb-3">Peserta</th>
                                <th class="text-right pb-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($events as $event)
                                <tr class="text-sm hover:bg-white/5 transition-colors">
                                    <td class="py-4">
                                        <div class="flex items-center gap-3">
                                            @if($event->poster_image)
                                                <img src="{{ Storage::url($event->poster_image) }}" alt="Poster" class="w-12 h-12 rounded-lg object-cover">
                                            @else
                                                <div class="w-12 h-12 rounded-lg bg-slate-800 flex items-center justify-center text-white/20">
                                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-bold text-white">{{ $event->title }}</div>
                                                <a href="{{ route('community.events.show', $event) }}" class="text-xs text-green-400 hover:underline" target="_blank">Lihat Halaman</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <div class="text-white/80">{{ $event->date->format('d M Y') }}</div>
                                        <div class="text-xs text-white/40">{{ substr($event->time, 0, 5) }} WIB</div>
                                    </td>
                                    <td class="py-4 text-white/60">
                                        {{ $event->location }}
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="text-white/80 font-bold">{{ $event->registrations_count }}</div>
                                            <div class="text-xs text-white/40">/ {{ $event->quota > 0 ? $event->quota : '∞' }}</div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.events.edit', $event) }}" class="p-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg transition-colors" title="Edit">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini? Semua data pendaftaran akan ikut terhapus.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-lg transition-colors" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $events->links() }}
                </div>
            @else
                <div class="text-center py-12 text-white/30">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-lg font-medium">Belum ada event</p>
                    <p class="text-sm mt-1">Buat event pertama Anda sekarang</p>
                    <a href="{{ route('admin.events.create') }}" class="inline-block mt-4 btn-primary font-bold text-white px-6 py-2 rounded-xl text-sm">
                        Buat Event Baru
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
