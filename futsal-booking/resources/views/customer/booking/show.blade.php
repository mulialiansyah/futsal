<x-app-layout>
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('customer.booking.index') }}" class="text-white/70 hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h2 class="text-2xl sm:text-3xl font-bold text-white">Detail Booking</h2>
    </div>

    <div class="max-w-3xl mx-auto space-y-5">
        <div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl p-6">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                    <p class="text-xs text-neutral-400 mb-1">Lapangan</p>
                    <p class="font-bold text-white">{{ $booking->lapangan->nama_lapangan }}</p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                    <p class="text-xs text-neutral-400 mb-1">Tanggal Main</p>
                    <p class="font-bold text-white">{{ \Carbon\Carbon::parse($booking->tanggal_main)->isoFormat('dddd, D MMMM YYYY') }}</p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                    <p class="text-xs text-neutral-400 mb-1">Jam Main</p>
                    <p class="font-bold text-white">{{ substr($booking->jam_mulai, 0, 5) }} – {{ substr($booking->jam_selesai, 0, 5) }}</p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                    <p class="text-xs text-neutral-400 mb-1">Status</p>
                    @php
                        $colors = [
                            'pending' => 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30',
                            'dp_dibayar' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                            'lunas' => 'bg-green-500/20 text-green-300 border-green-500/30',
                            'expired' => 'bg-red-500/20 text-red-300 border-red-500/30',
                            'batal' => 'bg-neutral-500/20 text-neutral-300 border-neutral-500/30'
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-bold border {{ $colors[$booking->status_booking] ?? 'bg-neutral-500/20' }}">
                        {{ ucfirst(str_replace('_', ' ', $booking->status_booking)) }}
                    </span>
                </div>
            </div>

            <div class="border-t border-white/10 pt-4 mb-6">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-neutral-400">Total Harga:</span>
                    <span class="font-bold text-white">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-neutral-400">Total Dibayar:</span>
                    <span class="font-bold text-green-300">Rp {{ number_format($booking->total_dibayar, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-lg border-t border-white/10 pt-2 mt-2">
                    <span class="font-bold text-neutral-300">Sisa Tagihan:</span>
                    <span class="font-extrabold text-red-300">Rp {{ number_format($booking->sisa_tagihan, 0, ',', '.') }}</span>
                </div>
            </div>

            @if($booking->status_booking === 'pending' && $booking->payment_deadline)
                <div class="bg-yellow-500/20 border border-yellow-500/30 rounded-xl p-6 mb-6">
                    <p class="text-sm text-yellow-300 mb-2">⏰ Sisa Waktu Pembayaran:</p>
                    <div class="text-5xl font-mono font-bold text-yellow-300 text-center mb-2" id="countdownDisplay">
                        {{ $booking->sisa_waktu_format }}
                    </div>
                    <p class="text-xs text-neutral-400 text-center">Deadline: {{ $booking->payment_deadline->format('d M Y H:i') }}</p>
                </div>
            @endif

            @if($booking->status_booking === 'dp_dibayar' && $booking->pelunasan_deadline)
                <div class="bg-blue-500/20 border border-blue-500/30 rounded-xl p-6 mb-6">
                    <p class="text-sm text-blue-300 font-semibold mb-2">⏰ Waktu Sisa untuk Pelunasan:</p>
                    <div class="text-5xl font-mono font-bold text-blue-300 text-center mb-2" id="countdownPelunasanDisplay">
                        {{ $booking->sisa_waktu_pelunasan_format }}
                    </div>
                    <p class="text-xs text-neutral-400 text-center">Deadline: {{ $booking->pelunasan_deadline->format('d M Y H:i') }}</p>
                </div>
            @endif

            @if(in_array($booking->status_booking, ['expired', 'batal']))
                <div class="bg-red-500/20 border border-red-500/30 rounded-xl p-4 mb-6">
                    <p class="text-sm text-red-300 font-bold">
                        ❌ Booking ini sudah dibatalkan/expired. Slot lapangan telah di-release.
                    </p>
                </div>
            @endif

            <div class="flex gap-3">
                @if($booking->status_booking === 'pending')
                    <form method="POST" action="{{ route('customer.booking.destroy', $booking) }}"
                          onsubmit="return confirm('Yakin batalkan booking ini?');" class="flex-1">
                        @csrf @method('DELETE')
                        <button class="w-full bg-red-500 hover:bg-red-600 text-white font-bold px-6 py-3 rounded-xl transition">
                            Batalkan Booking
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Section Pembayaran --}}
        <div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-white">Riwayat Pembayaran</h3>
                
                @php
                    $hasPending = $booking->pembayarans->where('status_verifikasi', 'pending')->isNotEmpty();
                    $hasRejected = $booking->pembayarans->where('status_verifikasi', 'ditolak')->isNotEmpty();
                @endphp

                @if(in_array($booking->status_booking, ['pending', 'dp_dibayar']) && !$hasPending)
                    <a href="{{ route('customer.pembayaran.create', $booking) }}"
                       class="bg-amber-400 hover:bg-amber-500 text-neutral-900 px-4 py-2 rounded-xl text-sm font-bold transition">
                        💳 {{ $booking->status_booking === 'pending' ? 'Bayar DP / Lunas' : 'Bayar Pelunasan' }}
                    </a>
                @endif
            </div>

            @if($booking->pembayarans->isEmpty())
                <div class="bg-yellow-500/20 border border-yellow-500/30 rounded-xl p-4 mb-4 text-center">
                    <p class="text-sm text-yellow-300 font-semibold">
                        ⚠️ Belum ada bukti pembayaran.
                    </p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($booking->pembayarans as $idx => $payment)
                        <div class="border rounded-xl p-4 {{ $payment->status_verifikasi === 'diterima' ? 'border-green-500/30 bg-green-500/10' : ($payment->status_verifikasi === 'ditolak' ? 'border-red-500/30 bg-red-500/10' : 'border-white/10') }}">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="text-xs text-neutral-400">Pembayaran #{{ $idx + 1 }} - {{ $payment->created_at->format('d M Y H:i') }}</p>
                                    <p class="font-bold text-white text-lg">Rp {{ number_format($payment->nominal, 0, ',', '.') }}</p>
                                </div>
                                @php
                                    $verifikasi = [
                                        'pending'  => 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30',
                                        'diterima' => 'bg-green-500/20 text-green-300 border-green-500/30',
                                        'ditolak'  => 'bg-red-500/20 text-red-300 border-red-500/30',
                                    ];
                                    $label = [
                                        'pending'  => '⏳ Menunggu Verifikasi',
                                        'diterima' => '✅ Diterima',
                                        'ditolak'  => '❌ Ditolak',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-semibold border {{ $verifikasi[$payment->status_verifikasi] ?? 'bg-neutral-500/20' }}">
                                    {{ $label[$payment->status_verifikasi] ?? $payment->status_verifikasi }}
                                </span>
                            </div>

                            @if($payment->bukti_transfer)
                                <div class="mt-3">
                                    <p class="text-xs text-neutral-400 mb-2">Bukti Transfer:</p>
                                    <img src="{{ asset('storage/' . $payment->bukti_transfer) }}"
                                         class="max-w-xs rounded-xl border border-white/10 object-contain max-h-32">
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        function updateCountdown() {
            @if($booking->status_booking === 'pending' && $booking->payment_deadline)
                const deadline = new Date({{ $booking->payment_deadline->timestamp * 1000 }});
                const now = new Date();
                const sisa = deadline - now;

                if (sisa <= 0) {
                    document.getElementById('countdownDisplay').textContent = '00:00:00';
                } else {
                    const jam = Math.floor(sisa / (1000 * 60 * 60));
                    const menit = Math.floor((sisa % (1000 * 60 * 60)) / (1000 * 60));
                    const detik = Math.floor((sisa % (1000 * 60)) / 1000);
                    document.getElementById('countdownDisplay').textContent =
                        `${String(jam).padStart(2, '0')}:${String(menit).padStart(2, '0')}:${String(detik).padStart(2, '0')}`;
                }
            @endif

            @if($booking->status_booking === 'dp_dibayar' && $booking->pelunasan_deadline)
                const deadline2 = new Date({{ $booking->pelunasan_deadline->timestamp * 1000 }});
                const now2 = new Date();
                const sisa2 = deadline2 - now2;

                if (sisa2 <= 0) {
                    document.getElementById('countdownPelunasanDisplay').textContent = '00:00:00';
                } else {
                    const jam2 = Math.floor(sisa2 / (1000 * 60 * 60));
                    const menit2 = Math.floor((sisa2 % (1000 * 60 * 60)) / (1000 * 60));
                    const detik2 = Math.floor((sisa2 % (1000 * 60)) / 1000);
                    document.getElementById('countdownPelunasanDisplay').textContent =
                        `${String(jam2).padStart(2, '0')}:${String(menit2).padStart(2, '0')}:${String(detik2).padStart(2, '0')}`;
                }
            @endif
        }
        updateCountdown();
        setInterval(updateCountdown, 1000);
    </script>
</x-app-layout>
