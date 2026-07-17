<x-admin-layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="font-display text-3xl text-white">Edit Lapangan</h1>
        </div>
    </div>

    <div class="rounded-xl bg-neutral-900 border border-white/10 overflow-hidden p-6">
        <form action="{{ route('admin.lapangan.update', $lapangan) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="nama_lapangan" class="block text-sm font-medium text-neutral-400 mb-2">Nama Lapangan</label>
                <input type="text" name="nama_lapangan" id="nama_lapangan" value="{{ old('nama_lapangan', $lapangan->nama_lapangan) }}" required class="w-full bg-neutral-800 border border-white/10 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('nama_lapangan')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="kategori" class="block text-sm font-medium text-neutral-400 mb-2">Kategori</label>
                <select name="kategori" id="kategori" required class="w-full bg-neutral-800 border border-white/10 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Kategori</option>
                    <option value="standar" {{ old('kategori', $lapangan->kategori) === 'standar' ? 'selected' : '' }}>Standar</option>
                    <option value="internasional" {{ old('kategori', $lapangan->kategori) === 'internasional' ? 'selected' : '' }}>Internasional</option>
                </select>
                @error('kategori')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="jenis_lapangan" class="block text-sm font-medium text-neutral-400 mb-2">Jenis Lapangan</label>
                <select name="jenis_lapangan" id="jenis_lapangan" required class="w-full bg-neutral-800 border border-white/10 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Jenis</option>
                    <option value="sintetis" {{ old('jenis_lapangan', $lapangan->jenis_lapangan) === 'sintetis' ? 'selected' : '' }}>Sintetis</option>
                    <option value="vinyl" {{ old('jenis_lapangan', $lapangan->jenis_lapangan) === 'vinyl' ? 'selected' : '' }}>Vinyl</option>
                </select>
                @error('jenis_lapangan')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="tipe_venue" class="block text-sm font-medium text-neutral-400 mb-2">Tipe Venue</label>
                <select name="tipe_venue" id="tipe_venue" required class="w-full bg-neutral-800 border border-white/10 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Tipe</option>
                    <option value="indoor" {{ old('tipe_venue', $lapangan->tipe_venue) === 'indoor' ? 'selected' : '' }}>Indoor</option>
                    <option value="outdoor" {{ old('tipe_venue', $lapangan->tipe_venue) === 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                </select>
                @error('tipe_venue')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-neutral-400 mb-2">Gambar Saat Ini</label>
                @if($lapangan->image)
                    <img src="{{ Storage::url($lapangan->image) }}" alt="{{ $lapangan->nama_lapangan }}" class="w-32 h-32 object-cover rounded-lg border border-white/10 mb-2">
                @else
                    <span class="text-neutral-500">No Image</span>
                @endif
            </div>

            <div class="mb-8">
                <label for="image" class="block text-sm font-medium text-neutral-400 mb-2">Ganti Gambar (Opsional)</label>
                <input type="file" name="image" id="image" class="w-full bg-neutral-800 border border-white/10 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('image')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.lapangan.index') }}" class="bg-neutral-800 hover:bg-neutral-700 text-white px-6 py-2 rounded-lg border border-white/10 transition">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">Update</button>
            </div>
        </form>
    </div>
</x-admin-layout>
