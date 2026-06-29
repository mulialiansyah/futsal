<x-admin-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">{{ $lapangan->nama_lapangan }}</h1>
                <a href="{{ route('admin.lapangan.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">Kembali</a>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <p class="text-lg">{{ ucfirst($lapangan->kategori) }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Lapangan</label>
                <p class="text-lg">{{ ucfirst($lapangan->jenis_lapangan) }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Venue</label>
                <p class="text-lg">{{ ucfirst($lapangan->tipe_venue) }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar</label>
                @if($lapangan->image)
                    <img src="{{ Storage::url($lapangan->image) }}" alt="{{ $lapangan->nama_lapangan }}" class="w-64 h-64 object-cover">
                @else
                    <span class="text-gray-400">No Image</span>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
