<x-app-layout>
    <x-slot name="title">Tambah Event Baru — Admin</x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-2 text-white/50 hover:text-white transition-colors text-sm mb-4">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Daftar Event
            </a>
            <h1 class="text-3xl font-extrabold text-white">Tambah Event Baru</h1>
            <p class="text-white/50 mt-1">Buat event atau jadwal pertandingan padel baru untuk komunitas</p>
        </div>

        <div class="bg-slate-900 border border-white/10 rounded-3xl p-6 sm:p-8">
            <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-medium text-white/70 mb-2">Judul Event <span class="text-red-400">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                           class="w-full bg-slate-800 border @error('title') border-red-500 @else border-white/10 @enderror text-white rounded-xl px-4 py-3 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                           placeholder="Contoh: Fun Match Padel Sunday">
                    @error('title') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="grid sm:grid-cols-2 gap-6">
                    <div>
                        <label for="date" class="block text-sm font-medium text-white/70 mb-2">Tanggal <span class="text-red-400">*</span></label>
                        <input type="date" name="date" id="date" value="{{ old('date') }}" required
                               class="w-full bg-slate-800 border @error('date') border-red-500 @else border-white/10 @enderror text-white rounded-xl px-4 py-3 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 [color-scheme:dark]">
                        @error('date') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="time" class="block text-sm font-medium text-white/70 mb-2">Waktu (WIB) <span class="text-red-400">*</span></label>
                        <input type="time" name="time" id="time" value="{{ old('time') }}" required
                               class="w-full bg-slate-800 border @error('time') border-red-500 @else border-white/10 @enderror text-white rounded-xl px-4 py-3 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 [color-scheme:dark]">
                        @error('time') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-6">
                    <div>
                        <label for="location" class="block text-sm font-medium text-white/70 mb-2">Lokasi / Venue <span class="text-red-400">*</span></label>
                        <input type="text" name="location" id="location" value="{{ old('location') }}" required
                               class="w-full bg-slate-800 border @error('location') border-red-500 @else border-white/10 @enderror text-white rounded-xl px-4 py-3 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                               placeholder="Nama lapangan atau alamat">
                        @error('location') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="quota" class="block text-sm font-medium text-white/70 mb-2">Kuota Peserta <span class="text-red-400">*</span></label>
                        <input type="number" name="quota" id="quota" value="{{ old('quota', 0) }}" required min="0"
                               class="w-full bg-slate-800 border @error('quota') border-red-500 @else border-white/10 @enderror text-white rounded-xl px-4 py-3 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                               placeholder="Isi 0 untuk tanpa batas">
                        <p class="mt-1 text-xs text-white/40">Isi 0 jika tidak ada batas peserta</p>
                        @error('quota') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-white/70 mb-2">Deskripsi Lengkap</label>
                    <textarea name="description" id="description" rows="5"
                              class="w-full bg-slate-800 border @error('description') border-red-500 @else border-white/10 @enderror text-white rounded-xl px-4 py-3 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                              placeholder="Detail acara, persyaratan, dress code, dll.">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="poster_image" class="block text-sm font-medium text-white/70 mb-2">Poster / Gambar (Opsional)</label>
                    <input type="file" name="poster_image" id="poster_image" accept="image/jpeg,image/png,image/webp"
                           class="w-full bg-slate-800 border @error('poster_image') border-red-500 @else border-white/10 @enderror text-white rounded-xl px-4 py-3 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-500/20 file:text-green-400 hover:file:bg-green-500/30">
                    <p class="mt-1 text-xs text-white/40">Format: JPG, PNG, WEBP. Maks 2MB.</p>
                    @error('poster_image') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 border-t border-white/10 flex gap-4 justify-end">
                    <a href="{{ route('admin.events.index') }}" class="px-6 py-3 rounded-xl text-sm font-semibold text-white/70 hover:text-white hover:bg-white/10 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary text-white font-bold px-8 py-3 rounded-xl text-sm shadow-lg shadow-green-500/20 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
