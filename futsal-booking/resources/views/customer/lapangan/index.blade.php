<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Cari Lapangan
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Search & Filter --}}
            <div class="bg-white rounded-xl shadow p-5 mb-6">
                <form method="GET" action="{{ route('customer.lapangan.index') }}">
                    <div class="flex flex-wrap gap-3">
                        <div class="flex-1 min-w-48">
                            <input type="text" name="q"
                                   value="{{ $keyword }}"
                                   placeholder="🔍 Cari nama lapangan..."
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>

                        {{-- Filter Kategori --}}
                        <div class="flex gap-2">
                            <a href="{{ route('customer.lapangan.index', array_filter(['q' => $keyword])) }}"
                               class="px-4 py-2.5 rounded-lg text-sm font-semibold transition
                                      {{ !$kategori ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                Semua
                            </a>
                            <a href="{{ route('customer.lapangan.index', array_filter(['q' => $keyword, 'kategori' => 'standar'])) }}"
                               class="px-4 py-2.5 rounded-lg text-sm font-semibold transition
                                      {{ $kategori === 'standar' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                                Standar
                            </a>
                            <a href="{{ route('customer.lapangan.index', array_filter(['q' => $keyword, 'kategori' => 'internasional'])) }}"
                               class="px-4 py-2.5 rounded-lg text-sm font-semibold transition
                                      {{ $kategori === 'internasional' ? 'bg-red-600 text-white' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
                                Internasional
                            </a>
                        </div>

                        <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2.5 rounded-lg text-sm transition">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            {{-- Info Harga Hari Ini --}}
            <div class="mb-5 flex items-center gap-2">
                <span class="text-sm text-gray-500">Harga hari ini:</span>
                <span class="px-2 py-1 rounded-full text-xs font-bold
                    {{ $tipeHari === 'weekend' ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700' }}">
                    {{ $tipeHari === 'weekend' ? '🟠 Weekend / Tanggal Merah' : '🟢 Weekday' }}
                </span>
            </div>

            {{-- Grid Lapangan --}}
            @if($lapangans->isEmpty())
                <div class="text-center py-16 text-gray-400">
                    <div class="text-5xl mb-3">🔍</div>
                    <div class="font-semibold text-gray-500 text-lg">Lapangan tidak ditemukan</div>
                    <p class="text-sm mt-1">Coba ubah kata kunci atau filter.</p>
                    <a href="{{ route('customer.lapangan.index') }}"
                       class="inline-block mt-4 text-green-600 hover:underline text-sm">
                        Reset pencarian
                    </a>
                </div>
            @else
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($lapangans as $lapangan)
                        <a href="{{ route('customer.lapangan.show', $lapangan) }}"
                           class="bg-white rounded-xl shadow hover:shadow-lg transition group overflow-hidden">

                            {{-- Foto --}}
                            <div class="h-44 bg-gray-100 overflow-hidden relative">
                                @if($lapangan->fotoUtama)
                                    <img src="{{ $lapangan->fotoUtama->url }}"
                                         alt="{{ $lapangan->nama_lapangan }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <div class="text-center">
                                            <div class="text-4xl mb-1">⚽</div>
                                            <div class="text-xs">Belum ada foto</div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Badge kategori --}}
                                <div class="absolute top-2 left-2">
                                    <span class="px-2 py-1 rounded-full text-xs font-bold shadow
                                        {{ $lapangan->kategori === 'internasional'
                                            ? 'bg-red-500 text-white'
                                            : 'bg-blue-500 text-white' }}">
                                        {{ $lapangan->kategori_label }}
                                    </span>
                                </div>
                            </div>

                            {{-- Info --}}
                            <div class="p-4">
                                <h3 class="font-bold text-gray-800 text-base mb-1">
                                    {{ $lapangan->nama_lapangan }}
                                </h3>
                                <div class="flex gap-2 mb-3">
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full capitalize">
                                        {{ $lapangan->jenis_lapangan }}
                                    </span>
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full capitalize">
                                        {{ $lapangan->tipe_venue }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-xs text-gray-400">Mulai dari</div>
                                        <div class="font-extrabold text-green-600">
                                            @if(isset($tarifPreview[$lapangan->kategori]))
                                                Rp {{ number_format($tarifPreview[$lapangan->kategori]->harga, 0, ',', '.') }}/jam
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                    <span class="text-green-600 font-semibold text-sm group-hover:translate-x-1 transition">
                                        Lihat →
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
