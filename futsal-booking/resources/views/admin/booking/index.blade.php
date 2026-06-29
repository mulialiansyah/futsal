<x-admin-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-6">Daftar Booking</h1>

            <div class="overflow-x-auto">
                <table class="w-full bg-white border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">User</th>
                            <th class="px-4 py-2 text-left">Lapangan</th>
                            <th class="px-4 py-2 text-left">Tanggal</th>
                            <th class="px-4 py-2 text-left">Jam</th>
                            <th class="px-4 py-2 text-left">Total Harga</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr class="border-t border-gray-200">
                                <td class="px-4 py-2">{{ $booking->user->name }}</td>
                                <td class="px-4 py-2">{{ $booking->lapangan->nama_lapangan }}</td>
                                <td class="px-4 py-2">{{ $booking->tanggal_main->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</td>
                                <td class="px-4 py-2">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded text-xs font-semibold
                                        {{ $booking->status_booking === 'lunas' ? 'bg-green-100 text-green-800' : 
                                           ($booking->status_booking === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($booking->status_booking === 'batal' ? 'bg-red-100 text-red-800' : 
                                           'bg-gray-100 text-gray-800')) }}">
                                        {{ ucfirst($booking->status_booking) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('admin.booking.show', $booking) }}" class="text-blue-600 hover:underline">Lihat</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
