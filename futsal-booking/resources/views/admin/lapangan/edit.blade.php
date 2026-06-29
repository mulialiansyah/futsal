<x-admin-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-6">Edit Lapangan</h1>

            <form action="{{ route('admin.lapangan.update', $lapangan) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="nama_lapangan" class="block text-sm font-medium text-gray-700 mb-2">Nama Lapangan</label>
                    <input type="text" name="nama_lapangan" id="nama_lapangan" value="{{ old('nama_lapangan', $lapangan->nama_lapangan) }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    @error('nama_lapangan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="kategori" id="kategori" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        <option value="">Pilih Kategori</option>
                        <option value="standar" {{ old('kategori', $lapangan->kategori) === 'standar' ? 'selected' : '' }}>Standar</option>
                        <option value="internasional" {{ old('kategori', $lapangan->kategori) === 'internasional' ? 'selected' : '' }}>Internasional</option>
                    </select>
                    @error('kategori')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="jenis_lapangan" class="block text-sm font-medium text-gray-700 mb-2">Jenis Lapangan</label>
                    <select name="jenis_lapangan" id="jenis_lapangan" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        <option value="">Pilih Jenis</option>
                        <option value="sintetis" {{ old('jenis_lapangan', $lapangan->jenis_lapangan) === 'sintetis' ? 'selected' : '' }}>Sintetis</option>
                        <option value="vinyl" {{ old('jenis_lapangan', $lapangan->jenis_lapangan) === 'vinyl' ? 'selected' : '' }}>Vinyl</option>
                    </select>
                    @error('jenis_lapangan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="tipe_venue" class="block text-sm font-medium text-gray-700 mb-2">Tipe Venue</label>
                    <select name="tipe_venue" id="tipe_venue" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        <option value="">Pilih Tipe</option>
                        <option value="indoor" {{ old('tipe_venue', $lapangan->tipe_venue) === 'indoor' ? 'selected' : '' }}>Indoor</option>
                        <option value="outdoor" {{ old('tipe_venue', $lapangan->tipe_venue) === 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                    </select>
                    @error('tipe_venue')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini</label>
                    @if($lapangan->image)
                        <img src="{{ Storage::url($lapangan->image) }}" alt="{{ $lapangan->nama_lapangan }}" class="w-32 h-32 object-cover mb-2">
                    @else
                        <span class="text-gray-400">No Image</span>
                    @endif
                </div>

                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Ganti Gambar (Opsional)</label>
                    <input type="file" name="image" id="image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('admin.lapangan.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 mr-2">Batal</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
