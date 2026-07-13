<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Booking
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow p-6">

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs text-gray-500 mb-1">Lapangan</p>
                        <p class="font-bold text-gray-800">{{ $booking->lapangan->nama_lapangan }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs text-gray-500 mb-1">Tanggal Main</p>
                        <p class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($booking->tanggal_main)->isoFormat('dddd, D MMMM YYYY') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs text-gray-500 mb-1">Jam Main</p>
                        <p class="font-bold text-gray-800">{{ substr($booking->jam_mulai, 0, 5) }} – {{ substr($booking->jam_selesai, 0, 5) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs text-gray-500 mb-1">Status</p>
                        @php
                            $colors = [
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'dp_dibayar' => 'bg-blue-100 text-blue-700',
                                'lunas' => 'bg-green-100 text-green-700',
                                'expired' => 'bg-red-100 text-red-700',
                                'batal' => 'bg-gray-100 text-gray-700'
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-sm font-bold {{ $colors[$booking->status_booking] ?? 'bg-gray-100' }}">
                            {{ ucfirst(str_replace('_', ' ', $booking->status_booking)) }}
                        </span>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 mb-6">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Total Harga:</span>
                        <span class="font-bold text-gray-800">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Total Dibayar:</span>
                        <span class="font-bold text-green-600">Rp {{ number_format($booking->total_dibayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg border-t pt-2 mt-2">
                        <span class="font-bold text-gray-700">Sisa Tagihan:</span>
                        <span class="font-extrabold text-red-600">Rp {{ number_format($booking->sisa_tagihan, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if($booking->status_booking === 'pending' && $booking->payment_deadline)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                        <p class="text-sm text-gray-600 mb-2">⏰ Sisa Waktu Pembayaran:</p>
                        <div class="text-5xl font-mono font-bold text-yellow-600 text-center mb-2" id="countdownDisplay">
                            {{ $booking->sisa_waktu_format }}
                        </div>
                        <p class="text-xs text-gray-500 text-center">Deadline: {{ $booking->payment_deadline->format('d M Y H:i') }}</p>
                    </div>
                @endif

                @if($booking->status_booking === 'dp_dibayar' && $booking->pelunasan_deadline)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                        <p class="text-sm text-blue-800 font-semibold mb-2">⏰ Waktu Sisa untuk Pelunasan:</p>
                        <div class="text-5xl font-mono font-bold text-blue-600 text-center mb-2" id="countdownPelunasanDisplay">
                            {{ $booking->sisa_waktu_pelunasan_format }}
                        </div>
                        <p class="text-xs text-blue-500 text-center">Deadline: {{ $booking->pelunasan_deadline->format('d M Y H:i') }}</p>
                    </div>
                @endif

                @if(in_array($booking->status_booking, ['expired', 'batal']))
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <p class="text-sm text-red-700 font-bold">
                            ❌ Booking ini sudah dibatalkan/expired. Slot lapangan telah di-release.
                        </p>
                    </div>
                @endif

                <div class="flex gap-3">
                    @if($booking->status_booking === 'pending')
                        <form method="POST" action="{{ route('customer.booking.destroy', $booking) }}"
                              onsubmit="return confirm('Yakin batalkan booking ini?');" class="flex-1">
                            @csrf @method('DELETE')
                            <button class="w-full bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-3 rounded-lg transition">
                                Batalkan Booking
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Section Pembayaran --}}
            <div class="bg-white rounded-xl shadow p-6 mt-5">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-gray-700">Riwayat Pembayaran</h3>
                    
                    @php
                        $hasPending = $booking->pembayarans->where('status_verifikasi', 'pending')->isNotEmpty();
                        $hasRejected = $booking->pembayarans->where('status_verifikasi', 'ditolak')->isNotEmpty();
                    @endphp

                    @if(in_array($booking->status_booking, ['pending', 'dp_dibayar']) && !$hasPending)
                        <a href="{{ route('customer.pembayaran.create', $booking) }}"
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition">
                            💳 {{ $booking->status_booking === 'pending' ? 'Bayar DP / Lunas' : 'Bayar Pelunasan' }}
                        </a>
                    @endif
                </div>

                @if($booking->pembayarans->isEmpty())
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4 text-center">
                        <p class="text-sm text-yellow-700 font-semibold">
                            ⚠️ Belum ada bukti pembayaran.
                        </p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($booking->pembayarans as $idx => $payment)
                            <div class="border rounded-lg p-4 {{ $payment->status_verifikasi === 'diterima' ? 'border-green-200 bg-green-50' : ($payment->status_verifikasi === 'ditolak' ? 'border-red-200 bg-red-50' : 'border-gray-200') }}">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="text-xs text-gray-500">Pembayaran #{{ $idx + 1 }} - {{ $payment->created_at->format('d M Y H:i') }}</p>
                                        <p class="font-bold text-gray-800 text-lg">Rp {{ number_format($payment->nominal, 0, ',', '.') }}</p>
                                    </div>
                                    @php
                                        $verifikasi = [
                                            'pending'  => 'bg-yellow-100 text-yellow-700',
                                            'diterima' => 'bg-green-100 text-green-700',
                                            'ditolak'  => 'bg-red-100 text-red-700',
                                        ];
                                        $label = [
                                            'pending'  => '⏳ Menunggu Verifikasi',
                                            'diterima' => '✅ Diterima',
                                            'ditolak'  => '❌ Ditolak',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $verifikasi[$payment->status_verifikasi] ?? 'bg-gray-100' }}">
                                        {{ $label[$payment->status_verifikasi] ?? $payment->status_verifikasi }}
                                    </span>
                                </div>

                                @if($payment->bukti_transfer)
                                    <div class="mt-3">
                                        <p class="text-xs text-gray-400 mb-2">Bukti Transfer:</p>
                                        <img src="{{ asset('storage/' . $payment->bukti_transfer) }}"
                                             class="max-w-xs rounded-lg border border-gray-200 object-contain max-h-32">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
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