<x-admin-layout>

    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-6">Lapangan yang dikelola</h1>

    @if($lapangans->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            @foreach($lapangans as $lapangan)
                <div class="bg-white/10 border border-white/20 rounded-2xl overflow-hidden">
                    <div class="p-4 flex items-center gap-4">
                        <img src="{{ $lapangan->fotoUtama->url }}"
                             alt="{{ $lapangan->nama_lapangan }}"
                             class="w-24 h-24 object-cover rounded-xl">
                        <div class="flex-1">
                            <h3 class="font-bold text-white text-lg">{{ $lapangan->nama_lapangan }}</h3>
                        </div>
                    </div>
                    <div class="px-4 pb-4">
                        <a href="{{ route('admin.lapangan.edit', $lapangan) }}" class="block w-full text-center text-sm font-semibold bg-white/10 hover:bg-white/20 text-white py-2 rounded-xl transition">
                            Edit
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white/10 border border-white/20 rounded-2xl p-8 text-center mb-6">
            <p class="text-neutral-400 mb-4">Belum ada lapangan yang dikelola.</p>
        </div>
    @endif

    <a href="{{ route('admin.lapangan.create') }}" class="inline-flex items-center justify-center gap-2 w-full px-6 py-3 rounded-xl bg-amber-400 text-neutral-950 font-bold hover:bg-amber-300 transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Tambahkan Lapangan
    </a>

</x-admin-layout>
