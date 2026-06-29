<x-admin-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-6">Edit Hari Libur</h1>

            <form action="{{ route('admin.hari-libur.update', $hariLibur) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $hariLibur->tanggal->format('Y-m-d')) }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    @error('tanggal')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <input type="text" name="keterangan" id="keterangan" value="{{ old('keterangan', $hariLibur->keterangan) }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    @error('keterangan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="tipe" class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
                    <select name="tipe" id="tipe" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        <option value="">Pilih Tipe</option>
                        <option value="nasional" {{ old('tipe', $hariLibur->tipe) === 'nasional' ? 'selected' : '' }}>Nasional</option>
                        <option value="cuti_bersama" {{ old('tipe', $hariLibur->tipe) === 'cuti_bersama' ? 'selected' : '' }}>Cuti Bersama</option>
                    </select>
                    @error('tipe')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('admin.hari-libur.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 mr-2">Batal</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
