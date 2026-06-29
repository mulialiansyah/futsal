<x-admin-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-blue-50 p-6 rounded-lg">
                    <div class="text-3xl font-bold text-blue-600">{{ $totalBookings }}</div>
                    <div class="text-gray-600">Total Bookings</div>
                </div>
                <div class="bg-green-50 p-6 rounded-lg">
                    <div class="text-3xl font-bold text-green-600">{{ $totalLapangan }}</div>
                    <div class="text-gray-600">Total Lapangan</div>
                </div>
                <div class="bg-yellow-50 p-6 rounded-lg">
                    <div class="text-3xl font-bold text-yellow-600">{{ $pendingPayments }}</div>
                    <div class="text-gray-600">Pembayaran Pending</div>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-4">Recent Bookings</h2>
                <div class="overflow-x-auto">
                    <table class="w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left">User</th>
                                <th class="px-4 py-2 text-left">Lapangan</th>
                                <th class="px-4 py-2 text-left">Tanggal</th>
                                <th class="px-4 py-2 text-left">Jam</th>
                                <th class="px-4 py-2 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentBookings as $booking)
                                <tr class="border-t border-gray-200">
                                    <td class="px-4 py-2">{{ $booking->user->name }}</td>
                                    <td class="px-4 py-2">{{ $booking->lapangan->nama_lapangan }}</td>
                                    <td class="px-4 py-2">{{ $booking->tanggal_main->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2">{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 rounded text-xs font-semibold
                                            {{ $booking->status_booking === 'lunas' ? 'bg-green-100 text-green-800' : 
                                               ($booking->status_booking === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($booking->status_booking === 'batal' ? 'bg-red-100 text-red-800' : 
                                               'bg-gray-100 text-gray-800')) }}">
                                            {{ ucfirst($booking->status_booking) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
