<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('customer.lapangan.show', $lapangan) }}"
               class="text-gray-400 hover:text-gray-600 transition">←</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Cek Slot — {{ $lapangan->nama_lapangan }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- Pilih Tanggal --}}
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-bold text-gray-700 mb-3">📅 Pilih Tanggal</h3>
                <form method="GET" action="{{ route('customer.lapangan.slots', $lapangan) }}" class="flex gap-3 items-end">
                    <div class="flex-1">
                        <input type="date" name="tanggal"
                               value="{{ $tanggal }}"
                               min="{{ now()->addDays(2)->toDateString() }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        <p class="text-xs text-gray-400 mt-1">* Booking minimal H-2 dari tanggal main.</p>
                    </div>
                    <button type="submit"
                            class="bg-gray-800 hover:bg-gray-900 text-white font-semibold px-5 py-2.5 rounded-lg text-sm transition">
                        Lihat Slot
                    </button>
                </form>
            </div>

            {{-- Info Tanggal --}}
            <div class="flex items-center gap-3 flex-wrap">
                <span class="text-sm text-gray-600 font-semibold">
                    {{ $tanggalCarbon->isoFormat('dddd, D MMMM YYYY') }}
                </span>
                <span class="px-2 py-1 rounded-full text-xs font-bold
                    {{ $tipeHari === 'weekend' ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700' }}">
                    {{ $tipeHari === 'weekend' ? '🟠 Weekend' : '🟢 Weekday' }}
                </span>
                @if($isTutup)
                    <span class="px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                        🚫 Lapangan Ditutup
                    </span>
                @endif
            </div>

            {{-- Slot Grid --}}
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Ketersediaan Jam</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Jam operasional 08:00 – 21:00</p>
                </div>

                <div class="divide-y divide-gray-50">
                    @foreach($slots as $slot)
                        <div class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 transition">
                            {{-- Jam --}}
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-bold text-gray-700 w-28">
                                    {{ $slot['jam'] }} – {{ $slot['jam_selesai'] }}
                                </span>

                                {{-- Status Badge --}}
                                @if($slot['status'] === 'tersedia')
                                    <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                        ✅ Tersedia
                                    </span>
                                @elseif($slot['status'] === 'dipesan')
                                    <span class="px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                        🔴 Sudah Dipesan
                                    </span>
                                @elseif($slot['status'] === 'tutup')
                                    <span class="px-2 py-1 rounded-full text-xs font-bold bg-gray-200 text-gray-500">
                                        🔒 Tutup
                                    </span>
                                @endif
                            </div>

                            {{-- Tombol Booking (hanya untuk slot tersedia) --}}
                            @if($slot['status'] === 'tersedia')
                                <a href="{{ route('customer.booking.create', [
                                        'lapangan_id'  => $lapangan->id,
                                        'tanggal_main' => $tanggal,
                                        'jam_mulai'    => $slot['jam'],
                                   ]) }}"
                                   class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition">
                                    Pesan Jam Ini →
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Legenda --}}
            <div class="flex flex-wrap gap-4 text-xs text-gray-500">
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-green-400"></span> Tersedia
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-red-400"></span> Sudah Dipesan
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-gray-300"></span> Tutup / Pemeliharaan
                </div>
            </div>

            {{-- Tombol Kembali --}}
            <div class="flex gap-3">
                <a href="{{ route('customer.lapangan.show', $lapangan) }}"
                   class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-lg transition text-sm">
                    ← Kembali ke Detail
                </a>
                <a href="{{ route('customer.booking.create', ['lapangan_id' => $lapangan->id]) }}"
                   class="flex-1 text-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition text-sm">
                    ⚽ Booking Langsung
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
