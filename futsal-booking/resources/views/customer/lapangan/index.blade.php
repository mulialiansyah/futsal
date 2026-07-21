<x-app-layout>
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-white">
            Cari Lapangan
        </h2>
        <a href="{{ route('customer.lapangan.denah') }}"
           class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/15 border border-white/20 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition">
            <span aria-hidden="true">🗺️</span>
            Lihat Denah
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl p-6 mb-8">
        <form method="GET" action="{{ route('customer.lapangan.index') }}">
            <div class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-48">
                    <input type="text" name="q"
                           value="{{ $keyword }}"
                           placeholder="🔍 Cari nama lapangan..."
                           class="w-full bg-white/5 border border-white/20 text-white placeholder:text-white/50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                </div>

                <!-- Filter Kategori -->
                <div class="flex gap-2 flex-wrap">
                    <a href="{{ route('customer.lapangan.index', array_filter(['q' => $keyword])) }}"
                       class="px-4 py-3 rounded-xl text-sm font-semibold transition
                              {{ !$kategori ? 'bg-amber-400 text-neutral-900' : 'bg-white/10 text-white hover:bg-white/20 border border-white/20' }}">
                        Semua
                    </a>
                    <a href="{{ route('customer.lapangan.index', array_filter(['q' => $keyword, 'kategori' => 'standar'])) }}"
                       class="px-4 py-3 rounded-xl text-sm font-semibold transition
                              {{ $kategori === 'standar' ? 'bg-amber-400 text-neutral-900' : 'bg-white/10 text-white hover:bg-white/20 border border-white/20' }}">
                        Standar
                    </a>
                    <a href="{{ route('customer.lapangan.index', array_filter(['q' => $keyword, 'kategori' => 'internasional'])) }}"
                       class="px-4 py-3 rounded-xl text-sm font-semibold transition
                              {{ $kategori === 'internasional' ? 'bg-amber-400 text-neutral-900' : 'bg-white/10 text-white hover:bg-white/20 border border-white/20' }}">
                        Internasional
                    </a>
                </div>

                <button type="submit"
                        class="bg-amber-400 hover:bg-amber-500 text-neutral-900 font-semibold px-6 py-3 rounded-xl text-sm transition shadow-lg shadow-amber-400/20">
                    Cari
                </button>
            </div>
        </form>
    </div>

    <!-- Info Harga Hari Ini -->
    <div class="mb-6 flex items-center gap-3">
        <span class="text-sm text-neutral-400">Harga hari ini:</span>
        <span class="px-3 py-1.5 rounded-full text-xs font-bold
            {{ $tipeHari === 'weekend' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-green-500/20 text-green-300 border border-green-500/30' }}">
            {{ $tipeHari === 'weekend' ? '🟠 Weekend / Tanggal Merah' : '🟢 Weekday' }}
        </span>
    </div>

    <!-- Grid Lapangan -->
    @if($lapangans->isEmpty())
        <div class="text-center py-16 bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl">
            <div class="text-5xl mb-3">🔍</div>
            <div class="font-semibold text-white text-lg">Lapangan tidak ditemukan</div>
            <p class="text-neutral-400 text-sm mt-2">Coba ubah kata kunci atau filter.</p>
            <a href="{{ route('customer.lapangan.index') }}"
               class="inline-block mt-4 text-amber-400 hover:text-amber-300 text-sm font-semibold">
                Reset pencarian
            </a>
        </div>
    @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($lapangans as $lapangan)
                <a href="{{ route('customer.lapangan.show', $lapangan) }}"
                   class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl overflow-hidden hover:bg-white/15 transition group">

                    <!-- Foto -->
                    <div class="h-48 bg-neutral-800 overflow-hidden relative">
                        @if($lapangan->fotoUtama)
                            <img src="{{ $lapangan->fotoUtama->url }}"
                                 alt="{{ $lapangan->nama_lapangan }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-neutral-500">
                                <div class="text-center">
                                    <div class="text-4xl mb-1">⚽</div>
                                    <div class="text-xs">Belum ada foto</div>
                                </div>
                            </div>
                        @endif

                        <!-- Badge kategori -->
                        <div class="absolute top-3 left-3">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold shadow
                                {{ $lapangan->kategori === 'internasional' ? 'bg-red-500 text-white' : 'bg-blue-500 text-white' }}">
                                {{ $lapangan->kategori_label }}
                            </span>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="p-5">
                        <h3 class="font-bold text-white text-lg mb-2">
                            {{ $lapangan->nama_lapangan }}
                        </h3>
                        <div class="flex gap-2 mb-3">
                            <span class="text-xs bg-white/10 text-neutral-300 px-2 py-1 rounded-full capitalize">
                                {{ $lapangan->jenis_lapangan }}
                            </span>
                            <span class="text-xs bg-white/10 text-neutral-300 px-2 py-1 rounded-full capitalize">
                                {{ $lapangan->tipe_venue }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs text-neutral-400">Mulai dari</div>
                                <div class="font-extrabold text-amber-400 text-lg">
                                    @if(isset($tarifPreview[$lapangan->kategori]))
                                        Rp {{ number_format($tarifPreview[$lapangan->kategori]->harga, 0, ',', '.') }}/jam
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <span class="text-amber-400 font-semibold text-sm group-hover:translate-x-1 transition">
                                Lihat →
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</x-app-layout>
