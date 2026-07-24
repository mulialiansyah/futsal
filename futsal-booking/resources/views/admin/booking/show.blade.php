<x-admin-layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="font-display text-3xl text-white">Detail Booking</h1>
        </div>
        <a href="{{ route('admin.booking.index') }}" class="bg-neutral-800 hover:bg-neutral-700 text-white px-4 py-2 rounded-lg border border-white/10 transition">Kembali</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="rounded-xl bg-neutral-900 border border-white/10 overflow-hidden p-6">
            <h2 class="font-display text-lg text-white tracking-wide mb-4">Informasi Booking</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-neutral-400 mb-1">User</label>
                    <p class="text-white">{{ $booking->user->name }} ({{ $booking->user->email }})</p>
                </div>

                <div>
                    <label class="block text-sm text-neutral-400 mb-1">Lapangan</label>
                    <p class="text-white">{{ $booking->lapangan->nama_lapangan }}</p>
                </div>

                <div>
                    <label class="block text-sm text-neutral-400 mb-1">Tanggal Main</label>
                    <p class="text-white">{{ $booking->tanggal_main->format('d/m/Y') }}</p>
                </div>

                <div>
                    <label class="block text-sm text-neutral-400 mb-1">Jam</label>
                    <p class="text-white">{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</p>
                </div>

                <div>
                    <label class="block text-sm text-neutral-400 mb-1">Total Harga</label>
                    <p class="text-white text-xl font-bold">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                </div>

                <div>
                    <label class="block text-sm text-neutral-400 mb-1">Status Booking</label>
                    <p class="text-white">
                        <span @class([
                            'px-2.5 py-1 rounded-full text-xs font-semibold border',
                            'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' => $booking->status_booking === 'lunas',
                            'bg-blue-500/10 text-blue-400 border-blue-500/20' => $booking->status_booking === 'dp_dibayar',
                            'bg-amber-500/10 text-amber-400 border-amber-500/20' => $booking->status_booking === 'pending',
                            'bg-red-500/10 text-red-400 border-red-500/20' => $booking->status_booking === 'batal' || $booking->status_booking === 'expired',
                            'bg-neutral-500/10 text-neutral-400 border-neutral-500/20' => !in_array($booking->status_booking, ['lunas', 'pending', 'batal', 'dp_dibayar', 'expired']),
                        ])>
                            {{ ucfirst(str_replace('_', ' ', $booking->status_booking)) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="rounded-xl bg-neutral-900 border border-white/10 overflow-hidden p-6">
            <h2 class="font-display text-lg text-white tracking-wide mb-4">Ringkasan Pembayaran</h2>
            
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-neutral-400">Total Harga</span>
                    <span class="font-semibold text-white">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-neutral-400">Total Dibayar</span>
                    <span class="font-semibold text-emerald-400">Rp {{ number_format($booking->total_dibayar, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between border-t border-white/10 pt-2 mt-2">
                    <span class="font-semibold text-neutral-300">Sisa Tagihan</span>
                    <span class="font-bold text-xl {{ $booking->sisa_tagihan > 0 ? 'text-red-400' : 'text-emerald-400' }}">
                        Rp {{ number_format($booking->sisa_tagihan, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tombol Konfirmasi Pelunasan Cash --}}
    @if($booking->status_booking === 'dp_dibayar' && $booking->sisa_tagihan > 0)
        <div class="rounded-xl bg-amber-500/10 border border-amber-500/20 p-6 mb-6">
            <h3 class="font-bold text-amber-400 mb-2">💵 Konfirmasi Pelunasan Cash</h3>
            <p class="text-sm text-amber-300 mb-3">
                Customer bayar sisa tagihan langsung di tempat (cash)? Klik tombol di bawah untuk konfirmasi.
            </p>
            <form action="{{ route('admin.pembayaran.confirm-cash', $booking) }}" method="POST"
                  data-confirm-message="Yakin konfirmasi pelunasan cash Rp {{ number_format($booking->sisa_tagihan, 0, ',', '.') }}?">
                @csrf
                <input type="hidden" name="nominal" value="{{ $booking->sisa_tagihan }}">
                <button type="submit"
                        class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-6 py-3 rounded-lg transition w-full">
                    💵 Konfirmasi Pelunasan Cash — Rp {{ number_format($booking->sisa_tagihan, 0, ',', '.') }}
                </button>
            </form>
        </div>
    @endif

    {{-- Ubah Status Manual --}}
    @if($booking->status_booking !== 'lunas' && $booking->status_booking !== 'batal' && $booking->status_booking !== 'expired')
        <div class="rounded-xl bg-neutral-900 border border-white/10 overflow-hidden p-6 mb-6">
            <h2 class="font-display text-lg text-white tracking-wide mb-4">Ubah Status</h2>
            <form action="{{ route('admin.booking.update-status', $booking) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <select name="status_booking" id="status_booking" required class="w-full bg-neutral-800 border border-white/10 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="pending" {{ $booking->status_booking === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="dp_dibayar" {{ $booking->status_booking === 'dp_dibayar' ? 'selected' : '' }}>DP Dibayar</option>
                        <option value="lunas" {{ $booking->status_booking === 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="batal" {{ $booking->status_booking === 'batal' ? 'selected' : '' }}>Batal</option>
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">Update Status</button>
            </form>
        </div>
    @endif

    {{-- Riwayat Pembayaran --}}
    <div class="rounded-xl bg-neutral-900 border border-white/10 overflow-hidden">
        <div class="px-6 py-4 border-b border-white/10">
            <h2 class="font-display text-lg text-white tracking-wide">Riwayat Pembayaran</h2>
        </div>
        <div class="p-6">
            @if($booking->pembayarans->isEmpty())
                <p class="text-neutral-500 text-sm">Belum ada pembayaran.</p>
            @else
                <div class="space-y-3">
                    @foreach($booking->pembayarans as $idx => $payment)
                        <div class="border border-white/10 rounded-lg p-4 {{ $payment->status_verifikasi === 'diterima' ? 'border-emerald-500/20 bg-emerald-500/5' : ($payment->status_verifikasi === 'ditolak' ? 'border-red-500/20 bg-red-500/5' : 'bg-neutral-800') }}">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs text-neutral-400">
                                        Pembayaran #{{ $idx + 1 }} — {{ $payment->created_at->format('d M Y H:i') }}
                                        @if(!$payment->bukti_transfer)
                                            <span class="ml-2 bg-amber-500/10 text-amber-400 px-2 py-0.5 rounded-full text-xs font-bold border border-amber-500/20">CASH</span>
                                        @endif
                                    </p>
                                    <p class="font-bold text-white text-xl mt-1">Rp {{ number_format($payment->nominal, 0, ',', '.') }}</p>
                                </div>
                                <span @class([
                                    'px-2.5 py-1 rounded-full text-xs font-semibold border',
                                    'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' => $payment->status_verifikasi === 'diterima',
                                    'bg-amber-500/10 text-amber-400 border-amber-500/20' => $payment->status_verifikasi === 'pending',
                                    'bg-red-500/10 text-red-400 border-red-500/20' => $payment->status_verifikasi === 'ditolak',
                                ])>
                                    {{ $payment->status_verifikasi === 'pending' ? '⏳ Pending' : ($payment->status_verifikasi === 'diterima' ? '✅ Diterima' : '❌ Ditolak') }}
                                </span>
                            </div>

                            @if($payment->bukti_transfer)
                                <div class="mt-3">
                                    <img src="{{ Storage::url($payment->bukti_transfer) }}" alt="Bukti Transfer" class="w-32 h-32 object-cover rounded-lg border border-white/10">
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
