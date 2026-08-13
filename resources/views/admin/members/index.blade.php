<x-app-layout>
    <x-slot name="title">Kelola Member — Admin</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <div class="text-xs text-amber-400 font-semibold uppercase tracking-wider mb-1">Admin Panel</div>
                <h1 class="text-3xl font-extrabold text-white">Kelola Member</h1>
            </div>
            <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-2 bg-slate-800 border border-white/10 hover:bg-slate-700 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition-colors self-start">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Kelola Event
            </a>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="bg-amber-500/10 border border-amber-500/20 rounded-2xl p-4 text-center">
                <div class="text-3xl font-black text-amber-400">{{ $pending->count() }}</div>
                <div class="text-xs text-white/50 mt-1">Menunggu</div>
            </div>
            <div class="bg-green-500/10 border border-green-500/20 rounded-2xl p-4 text-center">
                <div class="text-3xl font-black text-green-400">{{ $approved->count() }}</div>
                <div class="text-xs text-white/50 mt-1">Disetujui</div>
            </div>
            <div class="bg-red-500/10 border border-red-500/20 rounded-2xl p-4 text-center">
                <div class="text-3xl font-black text-red-400">{{ $rejected->count() }}</div>
                <div class="text-xs text-white/50 mt-1">Ditolak</div>
            </div>
        </div>

        {{-- Pending Members --}}
        <div class="bg-slate-900 border border-amber-500/20 rounded-3xl p-6 mb-6">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></div>
                <h2 class="text-lg font-bold text-white">Menunggu Persetujuan</h2>
                @if($pending->count())
                    <span class="ml-auto bg-amber-500/20 text-amber-400 text-xs font-bold px-2.5 py-1 rounded-full">{{ $pending->count() }}</span>
                @endif
            </div>

            @if($pending->count())
                <div class="space-y-3">
                    @foreach($pending as $user)
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 bg-slate-800 border border-white/5 rounded-2xl p-4">
                            <div class="w-10 h-10 rounded-full bg-amber-500/20 border border-amber-500/30 flex items-center justify-center text-amber-400 font-black shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-white">{{ $user->name }}</div>
                                <div class="text-white/50 text-sm">{{ $user->email }}</div>
                                <div class="flex flex-wrap gap-3 mt-1 text-xs text-white/40">
                                    @if($user->phone) <span>📱 {{ $user->phone }}</span> @endif
                                    @if($user->address) <span>📍 {{ $user->address }}</span> @endif
                                    <span>🕐 {{ $user->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="flex gap-2 shrink-0">
                                <form method="POST" action="{{ route('admin.members.approve', $user) }}">
                                    @csrf
                                    <button type="submit" class="bg-green-500/20 border border-green-500/40 text-green-400 hover:bg-green-500/30 font-semibold px-4 py-2 rounded-xl text-sm transition-colors">
                                        ✓ Setujui
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.members.reject', $user) }}">
                                    @csrf
                                    <button type="submit" class="bg-red-500/10 border border-red-500/30 text-red-400 hover:bg-red-500/20 font-semibold px-4 py-2 rounded-xl text-sm transition-colors"
                                            onclick="return confirm('Tolak pendaftaran {{ $user->name }}?')">
                                        ✕ Tolak
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-white/30">
                    <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm">Tidak ada pendaftar yang menunggu</p>
                </div>
            @endif
        </div>

        {{-- Approved Members --}}
        <div class="bg-slate-900 border border-white/10 rounded-3xl p-6 mb-6">
            <h2 class="text-lg font-bold text-white mb-5">Member Aktif ({{ $approved->count() }})</h2>
            @if($approved->count())
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-xs text-white/40 uppercase tracking-wider border-b border-white/10">
                                <th class="text-left pb-3">Nama</th>
                                <th class="text-left pb-3">Email</th>
                                <th class="text-left pb-3">No. HP</th>
                                <th class="text-left pb-3">Domisili</th>
                                <th class="text-left pb-3">Bergabung</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($approved as $user)
                                <tr class="text-sm">
                                    <td class="py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-green-500/20 flex items-center justify-center text-green-400 text-xs font-bold shrink-0">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <span class="font-medium text-white">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 text-white/60">{{ $user->email }}</td>
                                    <td class="py-3 text-white/60">{{ $user->phone ?? '-' }}</td>
                                    <td class="py-3 text-white/60">{{ $user->address ?? '-' }}</td>
                                    <td class="py-3 text-white/40 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-white/30 text-sm text-center py-4">Belum ada member aktif</p>
            @endif
        </div>

        {{-- Rejected Members --}}
        @if($rejected->count())
            <div class="bg-slate-900 border border-white/10 rounded-3xl p-6">
                <h2 class="text-lg font-bold text-white/50 mb-4">Ditolak ({{ $rejected->count() }})</h2>
                <div class="space-y-2">
                    @foreach($rejected as $user)
                        <div class="flex items-center gap-4 bg-slate-800/50 rounded-xl p-3">
                            <div class="w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center text-red-400 text-xs font-bold shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-white/60 font-medium text-sm">{{ $user->name }}</div>
                                <div class="text-white/30 text-xs">{{ $user->email }}</div>
                            </div>
                            <form method="POST" action="{{ route('admin.members.approve', $user) }}">
                                @csrf
                                <button type="submit" class="text-xs text-green-400 hover:text-green-300 font-medium transition-colors">Approve ulang</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
