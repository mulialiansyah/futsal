<x-admin-layout>
    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-6">Daftar Booking</h1>

    <div class="bg-white/10 rounded-2xl border border-white/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white/20 bg-white/5">
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-300">User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-300">Lapangan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-300">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-300">Jam</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-300">Total Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-300">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @foreach($bookings as $booking)
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-6 py-3.5 text-white font-medium">{{ $booking->user->name }}</td>
                            <td class="px-6 py-3.5 text-neutral-300">{{ $booking->lapangan->nama_lapangan }}</td>
                            <td class="px-6 py-3.5 text-neutral-400">{{ $booking->tanggal_main->format('d/m/Y') }}</td>
                            <td class="px-6 py-3.5 text-neutral-400">{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</td>
                            <td class="px-6 py-3.5 text-neutral-300 font-medium">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-3.5">
                                <span @class([
                                    'px-2.5 py-1 rounded-full text-xs font-semibold border',
                                    'bg-green-400/20 text-green-400 border-green-400/30' => $booking->status_booking === 'lunas',
                                    'bg-amber-400/20 text-amber-400 border-amber-400/30' => $booking->status_booking === 'pending' || $booking->status_booking === 'dp_dibayar',
                                    'bg-red-400/20 text-red-400 border-red-400/30' => $booking->status_booking === 'batal',
                                    'bg-white/10 text-neutral-300 border-white/20' => !in_array($booking->status_booking, ['lunas', 'pending', 'dp_dibayar', 'batal']),
                                ])>
                                    {{ ucfirst(str_replace('_', ' ', $booking->status_booking)) }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <a href="{{ route('admin.booking.show', $booking) }}" class="text-sky-400 hover:text-sky-300 transition font-medium">Lihat</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
