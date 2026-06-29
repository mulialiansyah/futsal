<x-admin-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-6">Tambah Tarif</h1>

            <form action="{{ route('admin.tarif.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="kategori" id="kategori" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        <option value="">Pilih Kategori</option>
                        <option value="standar" {{ old('kategori') === 'standar' ? 'selected' : '' }}>Standar</option>
                        <option value="internasional" {{ old('kategori') === 'internasional' ? 'selected' : '' }}>Internasional</option>
                    </select>
                    @error('kategori')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="tipe_hari" class="block text-sm font-medium text-gray-700 mb-2">Tipe Hari</label>
                    <select name="tipe_hari" id="tipe_hari" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        <option value="">Pilih Tipe</option>
                        <option value="weekday" {{ old('tipe_hari') === 'weekday' ? 'selected' : '' }}>Weekday</option>
                        <option value="weekend" {{ old('tipe_hari') === 'weekend' ? 'selected' : '' }}>Weekend</option>
                    </select>
                    @error('tipe_hari')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai</label>
                    <input type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    @error('jam_mulai')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="jam_selesai" class="block text-sm font-medium text-gray-700 mb-2">Jam Selesai</label>
                    <input type="time" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    @error('jam_selesai')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                    <input type="number" name="harga" id="harga" value="{{ old('harga') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    @error('harga')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('admin.tarif.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 mr-2">Batal</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
