<x-admin-layout>
    <h1 class="text-2xl sm:text-3xl font-bold text-neutral-900 mb-6">Daftar Booking</h1>

    <div class="bg-white rounded-2xl border border-neutral-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-neutral-200 bg-neutral-50">
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Lapangan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Jam</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Total Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach($bookings as $booking)
                        <tr class="hover:bg-neutral-50 transition">
                            <td class="px-6 py-3.5 text-neutral-900 font-medium">{{ $booking->user->name }}</td>
                            <td class="px-6 py-3.5 text-neutral-700">{{ $booking->lapangan->nama_lapangan }}</td>
                            <td class="px-6 py-3.5 text-neutral-600">{{ $booking->tanggal_main->format('d/m/Y') }}</td>
                            <td class="px-6 py-3.5 text-neutral-600">{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</td>
                            <td class="px-6 py-3.5 text-neutral-700 font-medium">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-3.5">
                                <span @class([
                                    'px-2.5 py-1 rounded-full text-xs font-semibold border',
                                    'bg-green-100 text-green-700 border-green-200' => $booking->status_booking === 'lunas',
                                    'bg-amber-100 text-amber-700 border-amber-200' => $booking->status_booking === 'pending' || $booking->status_booking === 'dp_dibayar',
                                    'bg-red-100 text-red-700 border-red-200' => $booking->status_booking === 'batal',
                                    'bg-neutral-100 text-neutral-700 border-neutral-200' => !in_array($booking->status_booking, ['lunas', 'pending', 'dp_dibayar', 'batal']),
                                ])>
                                    {{ ucfirst(str_replace('_', ' ', $booking->status_booking)) }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <a href="{{ route('admin.booking.show', $booking) }}" class="text-blue-600 hover:text-blue-800 transition font-medium">Lihat</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
