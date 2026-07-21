<x-app-layout>
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-white">
            Riwayat Booking Saya
        </h2>
        <a href="{{ route('customer.booking.create') }}" class="bg-amber-400 hover:bg-amber-500 text-neutral-900 font-bold py-2 px-4 rounded-xl text-sm transition shadow-lg shadow-amber-400/20">
            + Booking Baru
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-500/20 border border-green-500/30 text-green-300 px-4 py-3 rounded-xl relative mb-6">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if($bookings->isEmpty())
        <div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl p-8 text-center">
            <div class="text-5xl mb-3">⚽</div>
            <div class="font-semibold text-white text-lg">Belum ada riwayat booking</div>
            <p class="text-neutral-400 text-sm mt-2">Yuk mulai main futsal!</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl p-6 hover:bg-white/15 transition">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-bold text-white text-lg">{{ $booking->lapangan->nama_lapangan }}</h3>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30',
                                        'dp_dibayar' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                                        'lunas' => 'bg-green-500/20 text-green-300 border-green-500/30',
                                        'expired' => 'bg-red-500/20 text-red-300 border-red-500/30',
                                        'batal' => 'bg-neutral-500/20 text-neutral-300 border-neutral-500/30'
                                    ];
                                    $statusColor = $statusColors[$booking->status_booking] ?? $statusColors['pending'];
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusColor }}">
                                    @php
                                        $statusLabels = [
                                            'pending' => 'Menunggu Pembayaran',
                                            'dp_dibayar' => 'DP Dibayar',
                                            'lunas' => 'Lunas',
                                            'expired' => 'Kedaluwarsa',
                                            'batal' => 'Dibatalkan'
                                        ];
                                    @endphp
                                    {{ $statusLabels[$booking->status_booking] ?? ucfirst(str_replace('_', ' ', $booking->status_booking)) }}
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-4 text-sm">
                                <div class="text-neutral-400">
                                    <span class="text-neutral-500">Tanggal:</span>
                                    <span class="text-white font-medium ml-1">{{ \Carbon\Carbon::parse($booking->tanggal_main)->isoFormat('D MMM YYYY') }}</span>
                                </div>
                                <div class="text-neutral-400">
                                    <span class="text-neutral-500">Jam:</span>
                                    <span class="text-white font-medium ml-1">{{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }}</span>
                                </div>
                                <div class="text-neutral-400">
                                    <span class="text-neutral-500">Total:</span>
                                    <span class="text-white font-bold ml-1">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('customer.booking.show', $booking) }}" class="bg-white/10 hover:bg-white/20 text-white font-semibold py-2 px-4 rounded-xl text-sm transition border border-white/20">
                                Detail
                            </a>
                            
                            @php
                                $hasPending = $booking->pembayarans->where('status_verifikasi', 'pending')->isNotEmpty();
                                $hasRejected = $booking->pembayarans->where('status_verifikasi', 'ditolak')->isNotEmpty();
                            @endphp

                            @if(($booking->status_booking === 'pending' || $booking->status_booking === 'dp_dibayar') && !$hasPending)
                                @if($booking->status_booking === 'pending' && !$booking->isExpired())
                                    <a href="{{ route('customer.pembayaran.create', $booking) }}"
                                       class="bg-green-500/20 hover:bg-green-500/30 text-green-300 border border-green-500/30 font-semibold py-2 px-4 rounded-xl text-sm transition">
                                        💳 Bayar
                                    </a>
                                @elseif($booking->status_booking === 'dp_dibayar' && !$booking->isPelunasanExpired())
                                    <a href="{{ route('customer.pembayaran.create', $booking) }}"
                                       class="bg-blue-500/20 hover:bg-blue-500/30 text-blue-300 border border-blue-500/30 font-semibold py-2 px-4 rounded-xl text-sm transition">
                                        💳 Pelunasan
                                    </a>
                                @endif
                            @endif

                            @if($hasPending)
                                <span class="bg-yellow-500/20 text-yellow-300 border border-yellow-500/30 px-4 py-2 rounded-xl text-sm font-semibold">
                                    ⏰ Menunggu Verifikasi
                                </span>
                            @endif

                            @if($hasRejected && !$hasPending && in_array($booking->status_booking, ['pending', 'dp_dibayar']))
                                <a href="{{ route('customer.pembayaran.create', $booking) }}"
                                   class="bg-red-500/20 hover:bg-red-500/30 text-red-300 border border-red-500/30 font-semibold py-2 px-4 rounded-xl text-sm transition">
                                    🔄 Upload Ulang
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
