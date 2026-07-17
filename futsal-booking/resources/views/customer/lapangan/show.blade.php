<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('customer.lapangan.index') }}"
               class="text-gray-400 hover:text-gray-600 transition">←</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Lapangan
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- Foto Lapangan --}}
            <div class="bg-white rounded-xl shadow overflow-hidden">
                @if($lapangan->image)
                    <div class="h-72 bg-gray-100 overflow-hidden">
                        <img src="{{ asset('storage/' . $lapangan->image) }}"
                             class="w-full h-full object-cover"
                             alt="{{ $lapangan->nama_lapangan }}">
                    </div>
                @else
                    <div class="h-48 flex items-center justify-center text-gray-300 bg-gray-50">
                        <div class="text-center">
                            <div class="text-5xl mb-2">⚽</div>
                            <div class="text-sm">Belum ada foto lapangan</div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Info Lapangan --}}
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-800">
                            {{ $lapangan->nama_lapangan }}
                        </h1>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <span class="px-2 py-1 rounded-full text-xs font-bold
                                {{ $lapangan->kategori === 'internasional'
                                    ? 'bg-red-100 text-red-700'
                                    : 'bg-blue-100 text-blue-700' }}">
                                {{ $lapangan->kategori_label }}
                            </span>
                            <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-600 capitalize">
                                {{ $lapangan->jenis_lapangan }}
                            </span>
                            <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-600 capitalize">
                                {{ $lapangan->tipe_venue }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Spesifikasi --}}
                <div class="grid grid-cols-2 gap-3 mb-5">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="text-xs text-gray-400 mb-1">Jam Operasional</div>
                        <div class="font-bold text-gray-700">08:00 – 21:00</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="text-xs text-gray-400 mb-1">Kategori</div>
                        <div class="font-bold text-gray-700">{{ $lapangan->kategori_label }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="text-xs text-gray-400 mb-1">Jenis Permukaan</div>
                        <div class="font-bold text-gray-700 capitalize">{{ $lapangan->jenis_lapangan }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="text-xs text-gray-400 mb-1">Tipe Area</div>
                        <div class="font-bold text-gray-700 capitalize">{{ $lapangan->tipe_venue }}</div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('customer.lapangan.slots', $lapangan) }}"
                       class="flex-1 text-center bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 rounded-lg transition text-sm">
                        📅 Cek Slot Tersedia
                    </a>
                    <a href="{{ route('customer.booking.create', ['lapangan_id' => $lapangan->id]) }}"
                       class="flex-1 text-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition text-sm">
                        ⚽ Booking Sekarang
                    </a>
                </div>
            </div>

            {{-- Tabel Harga --}}
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Daftar Harga</h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Harga menyesuaikan hari dan jam booking
                    </p>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3 text-left">Tipe Hari</th>
                            <th class="px-5 py-3 text-left">Jam</th>
                            <th class="px-5 py-3 text-right">Harga/Jam</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($tarifs as $tarif)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $tarif->tipe_hari === 'weekend'
                                            ? 'bg-orange-100 text-orange-700'
                                            : 'bg-green-100 text-green-700' }}">
                                        {{ $tarif->tipe_hari === 'weekend' ? 'Weekend / Tanggal Merah' : 'Weekday' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-gray-600">
                                    {{ substr($tarif->jam_mulai, 0, 5) }} – {{ substr($tarif->jam_selesai, 0, 5) }}
                                </td>
                                <td class="px-5 py-3 text-right font-extrabold text-gray-800">
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
