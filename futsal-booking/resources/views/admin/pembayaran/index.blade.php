<x-admin-layout>
    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-6">Daftar Pembayaran</h1>

    <div class="bg-white/10 rounded-2xl border border-white/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white/20 bg-white/5">
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-300">Booking ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-300">User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-300">Nominal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-300">Status Verifikasi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @foreach($pembayarans as $pembayaran)
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-6 py-3.5 text-white font-medium">{{ $pembayaran->booking_id }}</td>
                            <td class="px-6 py-3.5 text-neutral-300">{{ $pembayaran->booking->user->name }}</td>
                            <td class="px-6 py-3.5 text-neutral-300 font-medium">Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</td>
                            <td class="px-6 py-3.5">
                                <span @class([
                                    'px-2.5 py-1 rounded-full text-xs font-semibold border',
                                    'bg-green-400/20 text-green-400 border-green-400/30' => $pembayaran->status_verifikasi === 'diterima',
                                    'bg-amber-400/20 text-amber-400 border-amber-400/30' => $pembayaran->status_verifikasi === 'pending',
                                    'bg-red-400/20 text-red-400 border-red-400/30' => $pembayaran->status_verifikasi === 'ditolak',
                                ])>
                                    {{ ucfirst($pembayaran->status_verifikasi) }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <a href="{{ route('admin.pembayaran.show', $pembayaran) }}" class="text-sky-400 hover:text-sky-300 transition font-medium">Lihat</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
