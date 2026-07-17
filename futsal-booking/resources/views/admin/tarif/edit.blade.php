<x-admin-layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="font-display text-3xl text-white">Edit Tarif</h1>
        </div>
    </div>

    <div class="rounded-xl bg-neutral-900 border border-white/10 overflow-hidden p-6">
        <form action="{{ route('admin.tarif.update', $tarif) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="kategori" class="block text-sm font-medium text-neutral-400 mb-2">Kategori</label>
                <select name="kategori" id="kategori" required class="w-full bg-neutral-800 border border-white/10 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Kategori</option>
                    <option value="standar" {{ old('kategori', $tarif->kategori) === 'standar' ? 'selected' : '' }}>Standar</option>
                    <option value="internasional" {{ old('kategori', $tarif->kategori) === 'internasional' ? 'selected' : '' }}>Internasional</option>
                </select>
                @error('kategori')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="tipe_hari" class="block text-sm font-medium text-neutral-400 mb-2">Tipe Hari</label>
                <select name="tipe_hari" id="tipe_hari" required class="w-full bg-neutral-800 border border-white/10 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Tipe</option>
                    <option value="weekday" {{ old('tipe_hari', $tarif->tipe_hari) === 'weekday' ? 'selected' : '' }}>Weekday</option>
                    <option value="weekend" {{ old('tipe_hari', $tarif->tipe_hari) === 'weekend' ? 'selected' : '' }}>Weekend</option>
                </select>
                @error('tipe_hari')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="jam_mulai" class="block text-sm font-medium text-neutral-400 mb-2">Jam Mulai</label>
                <input type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai', $tarif->jam_mulai) }}" required class="w-full bg-neutral-800 border border-white/10 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('jam_mulai')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="jam_selesai" class="block text-sm font-medium text-neutral-400 mb-2">Jam Selesai</label>
                <input type="time" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai', $tarif->jam_selesai) }}" required class="w-full bg-neutral-800 border border-white/10 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('jam_selesai')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-8">
                <label for="harga" class="block text-sm font-medium text-neutral-400 mb-2">Harga</label>
                <input type="number" name="harga" id="harga" value="{{ old('harga', $tarif->harga) }}" required class="w-full bg-neutral-800 border border-white/10 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('harga')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.tarif.index') }}" class="bg-neutral-800 hover:bg-neutral-700 text-white px-6 py-2 rounded-lg border border-white/10 transition">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">Update</button>
            </div>
        </form>
    </div>
</x-admin-layout>
