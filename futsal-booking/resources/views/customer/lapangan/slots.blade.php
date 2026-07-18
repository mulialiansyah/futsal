<x-app-layout>
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('customer.lapangan.show', $lapangan) }}" class="text-white/70 hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h2 class="text-2xl sm:text-3xl font-bold text-white">
            Cek Slot — {{ $lapangan->nama_lapangan }}
        </h2>
    </div>

    <div class="max-w-3xl mx-auto space-y-5">

        {{-- Pilih Tanggal --}}
        <div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl p-6">
            <h3 class="font-bold text-white mb-3">📅 Pilih Tanggal</h3>
            <form method="GET" action="{{ route('customer.lapangan.slots', $lapangan) }}" class="flex flex-col sm:flex-row gap-3 items-end">
                <div class="flex-1 w-full">
                    <input type="date" name="tanggal"
                           value="{{ $tanggal }}"
                           min="{{ now()->addDays(2)->toDateString() }}"
                           class="w-full bg-white/5 border border-white/20 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                    <p class="text-xs text-neutral-400 mt-1">* Booking minimal H-2 dari tanggal main.</p>
                </div>
                <button type="submit"
                        class="bg-amber-400 hover:bg-amber-500 text-neutral-900 font-semibold px-5 py-3 rounded-xl text-sm transition w-full sm:w-auto">
                    Lihat Slot
                </button>
            </form>
        </div>

        {{-- Info Tanggal --}}
        <div class="flex items-center gap-3 flex-wrap">
            <span class="text-sm text-neutral-300 font-semibold">
                {{ $tanggalCarbon->isoFormat('dddd, D MMMM YYYY') }}
            </span>
            <span class="px-2 py-1 rounded-full text-xs font-bold border
                {{ $tipeHari === 'weekend' ? 'bg-amber-500/20 text-amber-300 border-amber-500/30' : 'bg-green-500/20 text-green-300 border-green-500/30' }}">
                {{ $tipeHari === 'weekend' ? '🟠 Weekend' : '🟢 Weekday' }}
            </span>
            @if($isTutup)
                <span class="px-2 py-1 rounded-full text-xs font-bold border bg-red-500/20 text-red-300 border-red-500/30">
                    🚫 Lapangan Ditutup
                </span>
            @endif
        </div>

        {{-- Slot Grid --}}
        <div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-white/10">
                <h3 class="font-bold text-white">Ketersediaan Jam</h3>
                <p class="text-xs text-neutral-400 mt-0.5">Jam operasional 08:00 – 21:00</p>
            </div>

            <div class="divide-y divide-white/10">
                @foreach($slots as $slot)
                    <div class="flex items-center justify-between px-5 py-3 hover:bg-white/5 transition">
                        {{-- Jam --}}
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-bold text-white w-28">
                                {{ $slot['jam'] }} – {{ $slot['jam_selesai'] }}
                            </span>

                            {{-- Status Badge --}}
                            @if($slot['status'] === 'tersedia')
                                <span class="px-2 py-1 rounded-full text-xs font-bold border bg-green-500/20 text-green-300 border-green-500/30">
                                    ✅ Tersedia
                                </span>
                            @elseif($slot['status'] === 'dipesan')
                                <span class="px-2 py-1 rounded-full text-xs font-bold border bg-red-500/20 text-red-300 border-red-500/30">
                                    🔴 Sudah Dipesan
                                </span>
                            @elseif($slot['status'] === 'tutup')
                                <span class="px-2 py-1 rounded-full text-xs font-bold border bg-neutral-500/20 text-neutral-300 border-neutral-500/30">
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
                               class="bg-amber-400 hover:bg-amber-500 text-neutral-900 text-xs font-bold px-4 py-2 rounded-xl transition">
                                Pesan Jam Ini →
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Legenda --}}
        <div class="flex flex-wrap gap-4 text-xs text-neutral-400">
            <div class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-full bg-green-400"></span> Tersedia
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-full bg-red-400"></span> Sudah Dipesan
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-full bg-neutral-400"></span> Tutup / Pemeliharaan
            </div>
        </div>

        {{-- Tombol Kembali --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('customer.lapangan.show', $lapangan) }}"
               class="flex-1 text-center bg-white/10 hover:bg-white/20 text-neutral-300 font-semibold py-3 rounded-xl transition text-sm border border-white/20">
                ← Kembali ke Detail
            </a>
            <a href="{{ route('customer.booking.create', ['lapangan_id' => $lapangan->id]) }}"
               class="flex-1 text-center bg-amber-400 hover:bg-amber-500 text-neutral-900 font-bold py-3 rounded-xl transition text-sm">
                ⚽ Booking Langsung
            </a>
        </div>

    </div>
</x-app-layout>
