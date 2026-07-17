<x-admin-layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="font-display text-3xl text-white">Tambah Hari Libur</h1>
        </div>
    </div>

    <div class="rounded-xl bg-neutral-900 border border-white/10 overflow-hidden p-6">
        <form action="{{ route('admin.hari-libur.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="tanggal" class="block text-sm font-medium text-neutral-400 mb-2">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal') }}" required class="w-full bg-neutral-800 border border-white/10 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('tanggal')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="keterangan" class="block text-sm font-medium text-neutral-400 mb-2">Keterangan</label>
                <input type="text" name="keterangan" id="keterangan" value="{{ old('keterangan') }}" required class="w-full bg-neutral-800 border border-white/10 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('keterangan')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-8">
                <label for="tipe" class="block text-sm font-medium text-neutral-400 mb-2">Tipe</label>
                <select name="tipe" id="tipe" required class="w-full bg-neutral-800 border border-white/10 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Tipe</option>
                    <option value="nasional" {{ old('tipe') === 'nasional' ? 'selected' : '' }}>Nasional</option>
                    <option value="cuti_bersama" {{ old('tipe') === 'cuti_bersama' ? 'selected' : '' }}>Cuti Bersama</option>
                </select>
                @error('tipe')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.hari-libur.index') }}" class="bg-neutral-800 hover:bg-neutral-700 text-white px-6 py-2 rounded-lg border border-white/10 transition">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">Simpan</button>
            </div>
        </form>
    </div>
</x-admin-layout>
