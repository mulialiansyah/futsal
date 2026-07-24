<x-app-layout>
    <div class="mb-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-white">
            Riwayat Booking Saya
        </h2>
    </div>

    @if(session('success'))
        <div class="bg-green-500/20 border border-green-500/30 text-green-300 px-4 py-3 rounded-xl relative mb-6">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if($bookings->isEmpty())
        <div x-data="{ kicking: false }" @pointerdown="kicking = true; setTimeout(() => kicking = false, 420)" class="group bg-neutral-900 border border-neutral-800 rounded-2xl px-6 py-16 sm:py-20 text-center transition-colors cursor-pointer">
            <div class="mb-4 flex justify-center">
                <svg width="72" height="72" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" :class="kicking ? '-translate-y-1 -rotate-[8deg]' : ''" class="transition-transform duration-300 ease-out group-hover:-translate-y-1 group-hover:-rotate-[8deg]">
                    <circle cx="70" cy="78" r="9" fill="#F5A623" :class="kicking ? '-translate-x-2 -translate-y-1' : ''" class="transition-transform duration-300 ease-out group-hover:-translate-x-2 group-hover:-translate-y-1" />
                    <circle cx="42" cy="22" r="8" fill="#E8AD7D" />
                    <path d="M35 19 Q42 12 49 19 Q49 15 42 14 Q35 15 35 19 Z" fill="#2B1B12" />
                    <path d="M42 30 L40 52 L48 50 L50 30 Z" fill="#F5A623" />
                    <path d="M40 49 L38 56 L50 56 L48 49 Z" fill="#1E1E1E" />
                    <path d="M42 33 L28 40 L30 44 L44 39 Z" fill="#E8AD7D" />
                    <path d="M46 33 L58 26 L60 30 L48 38 Z" fill="#E8AD7D" />
                    <path d="M43 50 L38 66 L44 67 L48 51 Z" fill="#1E1E1E" />
                    <path d="M38 66 L36 78 L42 79 L44 67 Z" fill="#E8AD7D" />
                    <path d="M35 78 L43 79 L43 82 L34 82 Z" fill="#161616" />
                    <g :class="kicking ? 'rotate-[28deg]' : ''" class="origin-[47px_50px] transition-transform duration-300 ease-out group-hover:rotate-[28deg]">
                        <path d="M47 50 L55 60 L61 68 L56 72 L49 62 L44 51 Z" fill="#1E1E1E" />
                        <path d="M55 60 L61 68 L64 71 L58 74 L52 65 Z" fill="#E8AD7D" />
                        <path d="M60 68 L66 73 L64 76 L57 73 Z" fill="#161616" />
                    </g>
                </svg>
            </div>
            <div class="font-bold text-white text-xl sm:text-2xl">Belum ada riwayat booking</div>
            <p class="text-neutral-400 text-sm mt-2 mb-6">Yuk mulai main futsal!</p>
            <a href="{{ route('customer.booking.create') }}" class="inline-flex bg-amber-400 hover:bg-amber-500 text-neutral-900 font-bold py-3 px-7 rounded-xl text-sm transition shadow-lg shadow-amber-400/20">
                + Booking Baru
            </a>
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

                            @if($hasPending && $booking->status_booking !== 'batal')
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
