<x-admin-layout>
    <h1 class="text-2xl sm:text-3xl font-bold text-neutral-900 mb-6">Daftar Pembayaran</h1>

    <div class="bg-white rounded-2xl border border-neutral-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-neutral-200 bg-neutral-50">
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Booking ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Nominal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Status Verifikasi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach($pembayarans as $pembayaran)
                        <tr class="hover:bg-neutral-50 transition">
                            <td class="px-6 py-3.5 text-neutral-900 font-medium">{{ $pembayaran->booking_id }}</td>
                            <td class="px-6 py-3.5 text-neutral-700">{{ $pembayaran->booking->user->name }}</td>
                            <td class="px-6 py-3.5 text-neutral-700 font-medium">Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</td>
                            <td class="px-6 py-3.5">
                                <span @class([
                                    'px-2.5 py-1 rounded-full text-xs font-semibold border',
                                    'bg-green-100 text-green-700 border-green-200' => $pembayaran->status_verifikasi === 'diterima',
                                    'bg-amber-100 text-amber-700 border-amber-200' => $pembayaran->status_verifikasi === 'pending',
                                    'bg-red-100 text-red-700 border-red-200' => $pembayaran->status_verifikasi === 'ditolak',
                                ])>
                                    {{ ucfirst($pembayaran->status_verifikasi) }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <a href="{{ route('admin.pembayaran.show', $pembayaran) }}" class="text-blue-600 hover:text-blue-800 transition font-medium">Lihat</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
