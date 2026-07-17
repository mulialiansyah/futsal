<x-admin-layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="font-display text-3xl text-white">{{ $lapangan->nama_lapangan }}</h1>
        </div>
        <a href="{{ route('admin.lapangan.index') }}" class="bg-neutral-800 hover:bg-neutral-700 text-white px-4 py-2 rounded-lg border border-white/10 transition">Kembali</a>
    </div>

    <div class="rounded-xl bg-neutral-900 border border-white/10 overflow-hidden p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-6">
                <div>
                    <label class="block text-sm text-neutral-400 mb-2">Kategori</label>
                    <p class="text-white text-lg">{{ ucfirst($lapangan->kategori) }}</p>
                </div>

                <div>
                    <label class="block text-sm text-neutral-400 mb-2">Jenis Lapangan</label>
                    <p class="text-white text-lg">{{ ucfirst($lapangan->jenis_lapangan) }}</p>
                </div>

                <div>
                    <label class="block text-sm text-neutral-400 mb-2">Tipe Venue</label>
                    <p class="text-white text-lg">{{ ucfirst($lapangan->tipe_venue) }}</p>
                </div>
            </div>
            <div>
                <label class="block text-sm text-neutral-400 mb-2">Gambar</label>
                @if($lapangan->image)
                    <img src="{{ Storage::url($lapangan->image) }}" alt="{{ $lapangan->nama_lapangan }}" class="w-full h-64 object-cover rounded-lg border border-white/10">
                @else
                    <div class="w-full h-64 bg-neutral-800 rounded-lg flex items-center justify-center text-neutral-500">No Image</div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
