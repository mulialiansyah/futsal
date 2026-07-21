<x-admin-layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="font-display text-3xl text-white">Detail Pembayaran</h1>
        </div>
        <a href="{{ route('admin.pembayaran.index') }}" class="bg-neutral-800 hover:bg-neutral-700 text-white px-4 py-2 rounded-lg border border-white/10 transition">Kembali</a>
    </div>

    <div class="rounded-xl bg-neutral-900 border border-white/10 overflow-hidden p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-neutral-400 mb-2">Booking ID</label>
                    <p class="text-white">{{ $pembayaran->booking_id }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-neutral-400 mb-2">User</label>
                    <p class="text-white">{{ $pembayaran->booking->user->name }} ({{ $pembayaran->booking->user->email }})</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-neutral-400 mb-2">Nominal</label>
                    <p class="text-white text-xl font-bold">Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-neutral-400 mb-2">Metode Pembayaran</label>
                    <p class="text-white">{{ $pembayaran->metode_pembayaran === 'midtrans' ? 'Midtrans' : 'Transfer Manual' }}</p>
                    @if($pembayaran->metode_pembayaran === 'midtrans' && $pembayaran->midtrans_transaction_status)
                        <p class="text-xs text-neutral-400 mt-1">Status Midtrans: {{ $pembayaran->midtrans_transaction_status }}</p>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-neutral-400 mb-2">Status Verifikasi</label>
                    <p class="text-white">
                        <span @class([
                            'px-2.5 py-1 rounded-full text-xs font-semibold border',
                            'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' => $pembayaran->status_verifikasi === 'diterima',
                            'bg-amber-500/10 text-amber-400 border-amber-500/20' => $pembayaran->status_verifikasi === 'pending',
                            'bg-red-500/10 text-red-400 border-red-500/20' => $pembayaran->status_verifikasi === 'ditolak',
                        ])>
                            {{ ucfirst($pembayaran->status_verifikasi) }}
                        </span>
                    </p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-400 mb-2">Bukti Transfer</label>
                @if($pembayaran->bukti_transfer)
                    <img src="{{ Storage::url($pembayaran->bukti_transfer) }}" alt="Bukti Transfer" class="w-64 h-64 object-cover rounded-lg border border-white/10">
                @else
                    <div class="w-64 h-64 bg-neutral-800 rounded-lg flex items-center justify-center text-neutral-500 border border-white/10">No Bukti</div>
                @endif
            </div>
        </div>
    </div>

    @if($pembayaran->status_verifikasi === 'pending' && $pembayaran->metode_pembayaran !== 'midtrans')
        <div class="rounded-xl bg-neutral-900 border border-white/10 overflow-hidden p-6">
            <h2 class="font-display text-lg text-white tracking-wide mb-4">Verifikasi Pembayaran</h2>
            <form action="{{ route('admin.pembayaran.verify', $pembayaran) }}" method="POST" class="flex gap-3">
                @csrf
                @method('PATCH')
                <button type="submit" name="status_verifikasi" value="diterima" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg transition">Terima</button>
                <button type="submit" name="status_verifikasi" value="ditolak" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition">Tolak</button>
            </form>
        </div>
    @elseif($pembayaran->status_verifikasi === 'pending' && $pembayaran->metode_pembayaran === 'midtrans')
        <div class="rounded-xl bg-blue-500/10 border border-blue-500/20 p-6 text-sm text-blue-300">
            Pembayaran ini menunggu konfirmasi otomatis dari webhook Midtrans dan tidak dapat diverifikasi manual.
        </div>
    @endif
</x-admin-layout>
