<x-app-layout>
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('customer.lapangan.index') }}" class="text-white/70 hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h2 class="text-2xl sm:text-3xl font-bold text-white">Detail Lapangan</h2>
    </div>

    <div class="max-w-4xl mx-auto space-y-5">

        {{-- Foto Lapangan --}}
        <div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl overflow-hidden">
            <div class="h-72 bg-neutral-800 overflow-hidden">
                <img src="{{ $lapangan->fotoUtama->url }}"
                     class="w-full h-full object-cover"
                     alt="{{ $lapangan->nama_lapangan }}">
            </div>
        </div>

        {{-- Info Lapangan --}}
        <div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-extrabold text-white">
                        {{ $lapangan->nama_lapangan }}
                    </h1>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <span class="px-2 py-1 rounded-full text-xs font-bold border
                            {{ $lapangan->kategori === 'internasional'
                                ? 'bg-red-500/20 text-red-300 border-red-500/30'
                                : 'bg-blue-500/20 text-blue-300 border-blue-500/30' }}">
                            {{ $lapangan->kategori_label }}
                        </span>
                        <span class="px-2 py-1 rounded-full text-xs bg-white/10 text-neutral-300 capitalize">
                            {{ $lapangan->jenis_lapangan }}
                        </span>
                        <span class="px-2 py-1 rounded-full text-xs bg-white/10 text-neutral-300 capitalize">
                            {{ $lapangan->tipe_venue }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Spesifikasi --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-5">
                <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                    <div class="text-xs text-neutral-400 mb-1">Jam Operasional</div>
                    <div class="font-bold text-white">08:00 – 21:00</div>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                    <div class="text-xs text-neutral-400 mb-1">Kategori</div>
                    <div class="font-bold text-white">{{ $lapangan->kategori_label }}</div>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                    <div class="text-xs text-neutral-400 mb-1">Jenis Permukaan</div>
                    <div class="font-bold text-white capitalize">{{ $lapangan->jenis_lapangan }}</div>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                    <div class="text-xs text-neutral-400 mb-1">Tipe Area</div>
                    <div class="font-bold text-white capitalize">{{ $lapangan->tipe_venue }}</div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('customer.lapangan.slots', $lapangan) }}"
                   class="flex-1 text-center bg-white/10 hover:bg-white/20 text-white font-bold py-3 rounded-xl transition text-sm border border-white/20">
                    📅 Cek Slot Tersedia
                </a>
                <a href="{{ route('customer.booking.create', ['lapangan_id' => $lapangan->id]) }}"
                   class="flex-1 text-center bg-amber-400 hover:bg-amber-500 text-neutral-900 font-bold py-3 rounded-xl transition text-sm">
                    ⚽ Booking Sekarang
                </a>
            </div>
        </div>

        {{-- Tabel Harga --}}
        <div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-white/10">
                <h3 class="font-bold text-white">Daftar Harga</h3>
                <p class="text-xs text-neutral-400 mt-0.5">
                    Harga menyesuaikan hari dan jam booking
                </p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-white/5 text-neutral-400 uppercase text-xs border-b border-white/10">
                        <tr>
                            <th class="px-5 py-3 text-left">Tipe Hari</th>
                            <th class="px-5 py-3 text-left">Jam</th>
                            <th class="px-5 py-3 text-right">Harga/Jam</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @foreach($tarifs as $tarif)
                            <tr class="hover:bg-white/5 transition">
                                <td class="px-5 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold border
                                        {{ $tarif->tipe_hari === 'weekend'
                                            ? 'bg-amber-500/20 text-amber-300 border-amber-500/30'
                                            : 'bg-green-500/20 text-green-300 border-green-500/30' }}">
                                        {{ $tarif->tipe_hari === 'weekend' ? 'Weekend / Tanggal Merah' : 'Weekday' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-neutral-300">
                                    {{ substr($tarif->jam_mulai, 0, 5) }} – {{ substr($tarif->jam_selesai, 0, 5) }}
                                </td>
                                <td class="px-5 py-3 text-right font-extrabold text-amber-400">
                                    Rp {{ number_format($tarif->harga, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
