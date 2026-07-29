<x-admin-layout>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">Lapangan yang dikelola</h1>
            <p class="text-sm text-neutral-400 mt-1">Kelola data lapangan futsal aktif.</p>
        </div>
        <a href="{{ route('admin.lapangan.create') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-amber-400 text-neutral-950 font-bold hover:bg-amber-300 transition whitespace-nowrap">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Tambahkan Lapangan
        </a>
    </div>

    <form method="GET" action="{{ route('admin.lapangan.index') }}" class="flex flex-wrap items-center gap-3 mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari lapangan..." class="flex-1 min-w-[200px] px-4 py-2 rounded-lg bg-white/10 text-white placeholder:text-neutral-400 focus:outline-none focus:ring-2 focus:ring-amber-400/50" />
        <div class="relative">
            <select name="category" class="appearance-none pl-3 pr-10 py-2 rounded-lg bg-white/10 text-white focus:outline-none focus:ring-2 focus:ring-amber-400/50 cursor-pointer">
                <option value="" {{ request('category') == '' ? 'selected' : '' }} class="bg-neutral-800 text-white">Semua Kategori</option>
                <option value="standar" {{ request('category') == 'standar' ? 'selected' : '' }} class="bg-neutral-800 text-white hover:bg-neutral-700">Standar</option>
                <option value="internasional" {{ request('category') == 'internasional' ? 'selected' : '' }} class="bg-neutral-800 text-white hover:bg-neutral-700">Internasional</option>
            </select>
            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
        <button type="submit" class="px-4 py-2 bg-amber-400 text-neutral-950 rounded-lg hover:bg-amber-300 transition font-medium">Cari</button>
    </form>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-400/20 border border-green-400/30 text-green-400 rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if($viewMode === 'split')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="space-y-4">
                <h2 class="text-lg font-bold text-amber-400 border-b border-white/10 pb-2">Kategori Standar</h2>
                @if($standarLapangans->count() > 0)
                    <div class="flex flex-col gap-4">
                        @foreach($standarLapangans as $lapangan)
                            <div class="bg-white/10 border border-white/20 rounded-2xl overflow-hidden">
                                <div class="p-4 flex items-center gap-4">
                                    <img src="{{ $lapangan->fotoUtama->url }}" alt="{{ $lapangan->nama_lapangan }}" class="w-24 h-24 object-cover rounded-xl">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-white text-lg">{{ $lapangan->nama_lapangan }}</h3>
                                        <p class="text-xs text-neutral-400 capitalize mt-0.5">{{ $lapangan->kategori }} · {{ $lapangan->jenis_lapangan }} · {{ $lapangan->tipe_venue }}</p>
                                    </div>
                                </div>
                                <div class="px-4 pb-4 flex gap-2">
                                    <a href="{{ route('admin.lapangan.edit', $lapangan) }}" class="flex-1 text-center text-sm font-semibold bg-white/10 hover:bg-white/20 text-white py-2 rounded-xl transition">Edit</a>
                                    <form action="{{ route('admin.lapangan.destroy', $lapangan) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lapangan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-center text-sm font-semibold bg-red-500/20 hover:bg-red-500/30 text-red-400 py-2 rounded-xl transition">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @elseif($standarLapangans->total() > 0)
                    <div class="bg-white/10 border border-white/20 rounded-2xl p-8 text-center">
                        <p class="text-neutral-400">Tidak ada lapangan lagi di halaman ini.</p>
                    </div>
                @else
                    <div class="bg-white/10 border border-white/20 rounded-2xl p-8 text-center">
                        <p class="text-neutral-400">Belum ada lapangan kategori Standar.</p>
                    </div>
                @endif
            </div>

            <div class="space-y-4">
                <h2 class="text-lg font-bold text-blue-400 border-b border-white/10 pb-2">Kategori Internasional</h2>
                @if($internasionalLapangans->count() > 0)
                    <div class="flex flex-col gap-4">
                        @foreach($internasionalLapangans as $lapangan)
                            <div class="bg-white/10 border border-white/20 rounded-2xl overflow-hidden">
                                <div class="p-4 flex items-center gap-4">
                                    <img src="{{ $lapangan->fotoUtama->url }}" alt="{{ $lapangan->nama_lapangan }}" class="w-24 h-24 object-cover rounded-xl">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-white text-lg">{{ $lapangan->nama_lapangan }}</h3>
                                        <p class="text-xs text-neutral-400 capitalize mt-0.5">{{ $lapangan->kategori }} · {{ $lapangan->jenis_lapangan }} · {{ $lapangan->tipe_venue }}</p>
                                    </div>
                                </div>
                                <div class="px-4 pb-4 flex gap-2">
                                    <a href="{{ route('admin.lapangan.edit', $lapangan) }}" class="flex-1 text-center text-sm font-semibold bg-white/10 hover:bg-white/20 text-white py-2 rounded-xl transition">Edit</a>
                                    <form action="{{ route('admin.lapangan.destroy', $lapangan) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lapangan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-center text-sm font-semibold bg-red-500/20 hover:bg-red-500/30 text-red-400 py-2 rounded-xl transition">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @elseif($internasionalLapangans->total() > 0)
                    <div class="bg-white/10 border border-white/20 rounded-2xl p-8 text-center">
                        <p class="text-neutral-400">Tidak ada lapangan lagi di halaman ini.</p>
                    </div>
                @else
                    <div class="bg-white/10 border border-white/20 rounded-2xl p-8 text-center">
                        <p class="text-neutral-400">Belum ada lapangan kategori Internasional.</p>
                    </div>
                @endif
            </div>
        </div>

        @if(isset($mainPaginator) && method_exists($mainPaginator, 'links') && $mainPaginator->hasPages())
            <div class="mt-6">
                {{ $mainPaginator->links() }}
            </div>
        @endif
    @else
        @if($lapangans->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                @foreach($lapangans as $lapangan)
                    <div class="bg-white/10 border border-white/20 rounded-2xl overflow-hidden">
                        <div class="p-4 flex items-center gap-4">
                            <img src="{{ $lapangan->fotoUtama->url }}" alt="{{ $lapangan->nama_lapangan }}" class="w-24 h-24 object-cover rounded-xl">
                            <div class="flex-1">
                                <h3 class="font-bold text-white text-lg">{{ $lapangan->nama_lapangan }}</h3>
                                <p class="text-xs text-neutral-400 capitalize mt-0.5">{{ $lapangan->kategori }} · {{ $lapangan->jenis_lapangan }} · {{ $lapangan->tipe_venue }}</p>
                            </div>
                        </div>
                        <div class="px-4 pb-4 flex gap-2">
                            <a href="{{ route('admin.lapangan.edit', $lapangan) }}" class="flex-1 text-center text-sm font-semibold bg-white/10 hover:bg-white/20 text-white py-2 rounded-xl transition">Edit</a>
                            <form action="{{ route('admin.lapangan.destroy', $lapangan) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lapangan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-center text-sm font-semibold bg-red-500/20 hover:bg-red-500/30 text-red-400 py-2 rounded-xl transition">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            @if(method_exists($lapangans, 'links') && $lapangans->hasPages())
                <div class="mt-6">
                    {{ $lapangans->links() }}
                </div>
            @endif
        @else
            <div class="bg-white/10 border border-white/20 rounded-2xl p-8 text-center mb-6">
                <p class="text-neutral-400 mb-4">Belum ada lapangan yang dikelola.</p>
            </div>
        @endif
    @endif
</x-admin-layout>
