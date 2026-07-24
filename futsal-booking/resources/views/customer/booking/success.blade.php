<x-app-layout>
    @php
        $bookingCode = 'FK-'.str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT);
        $barcodeDigits = str_split((string) $booking->id.'2026'.str_pad((string) $booking->lapangan_id, 2, '0', STR_PAD_LEFT));
    @endphp

    <div class="flex min-h-[calc(100vh-12rem)] items-center justify-center py-8">
        <div class="w-full max-w-sm overflow-hidden rounded-2xl bg-[#F3F1E7] text-[#1C1C1C] shadow-[0_30px_60px_-15px_rgba(0,0,0,0.65)] ticket-rise">
            <div class="px-6 pb-5 pt-5">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[#1F7A4D]">Booking Berhasil</p>
                        <p class="mt-1 font-mono text-xs tracking-wide text-[#1C1C1C]/60">#{{ $bookingCode }}</p>
                    </div>
                    <span class="ticket-stamp rounded-md border-2 border-[#B8860B] px-2 py-1 text-[10px] font-bold uppercase tracking-widest text-[#B8860B]">
                        {{ $booking->metode_pembayaran === 'cash' ? 'Bayar di Lokasi' : 'Menunggu Bayar' }}
                    </span>
                </div>

                <div class="mt-5 flex items-end justify-between">
                    <div>
                        <p class="text-[11px] uppercase tracking-widest text-[#1C1C1C]/50">Lapangan</p>
                        <p class="mt-1 flex items-center gap-1.5 text-2xl font-bold leading-tight">
                            <span class="text-[#1F7A4D]">⌖</span>{{ $booking->lapangan->nama_lapangan }}
                        </p>
                        <p class="mt-0.5 text-xs text-[#1C1C1C]/50">{{ $booking->lapangan->kategori_label ?? 'Lapangan Futsal' }}</p>
                    </div>
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#1F7A4D] text-lg font-bold text-[#F3F1E7] ticket-check">✓</span>
                </div>

                <div class="mt-5 grid grid-cols-2 gap-x-4 gap-y-4">
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-[#1C1C1C]/50">Tanggal</p>
                        <p class="mt-0.5 text-sm font-medium">{{ \Carbon\Carbon::parse($booking->tanggal_main)->isoFormat('D MMM YYYY') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-[#1C1C1C]/50">Jam Main</p>
                        <p class="mt-0.5 font-mono text-sm font-medium">{{ substr($booking->jam_mulai, 0, 5) }} – {{ substr($booking->jam_selesai, 0, 5) }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-[#1C1C1C]/50">Atas Nama</p>
                        <p class="mt-0.5 truncate text-sm font-medium">{{ $booking->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-[#1C1C1C]/50">Total Booking</p>
                        <p class="mt-0.5 text-sm font-medium">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="relative h-px border-t-2 border-dashed border-[#1C1C1C]/20">
                <span class="absolute -left-3 -top-3 h-6 w-6 rounded-full bg-[#0B0F0C]"></span>
                <span class="absolute -right-3 -top-3 h-6 w-6 rounded-full bg-[#0B0F0C]"></span>
            </div>

            <div class="px-6 pb-6 pt-5">
                <div class="flex h-12 items-end gap-[2px]">
                    @foreach($barcodeDigits as $index => $digit)
                        <span class="bg-[#1C1C1C]" style="width: {{ ((int) $digit % 3 === 0) ? '3px' : '1.5px' }}; height: {{ ((int) $digit % 2 === 0) ? '100%' : '70%' }}"></span>
                    @endforeach
                </div>
                <p class="mt-2 text-center font-mono text-[11px] tracking-[0.35em] text-[#1C1C1C]/60">{{ $bookingCode }}</p>
            </div>
        </div>
    </div>

    <div class="mx-auto flex w-full max-w-sm flex-col gap-3 pb-8 sm:flex-row">
        @if($booking->metode_pembayaran !== 'cash')
            <a href="{{ route('customer.pembayaran.create', $booking) }}" class="flex-1 rounded-full bg-emerald-600 px-5 py-3 text-center text-sm font-bold text-white transition hover:bg-emerald-500">Lanjut Pembayaran</a>
        @endif
        <a href="{{ route('customer.booking.show', $booking) }}" class="flex-1 rounded-full border border-white/15 bg-white/5 px-5 py-3 text-center text-sm font-bold text-white transition hover:bg-white/10">Lihat Detail</a>
    </div>

    <style>
        @keyframes ticket-rise { from { opacity: 0; transform: translateY(28px) scale(.96); } to { opacity: 1; transform: translateY(0) scale(1); } }
        @keyframes stamp-pop { 0% { opacity: 0; transform: scale(1.8) rotate(-12deg); } 100% { opacity: 1; transform: scale(1) rotate(-12deg); } }
        @keyframes check-pop { 0% { opacity: 0; transform: scale(.5); } 100% { opacity: 1; transform: scale(1); } }
        .ticket-rise { animation: ticket-rise .7s cubic-bezier(.16,1,.3,1) forwards; }
        .ticket-stamp { animation: stamp-pop .48s .5s cubic-bezier(.34,1.56,.64,1) both; }
        .ticket-check { animation: check-pop .3s .7s ease-out both; }
    </style>
</x-app-layout>
