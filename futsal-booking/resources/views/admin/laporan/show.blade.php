<x-admin-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Laporan Penjualan</h1>
                <a href="{{ route('admin.laporan.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">Kembali</a>
            </div>

            <div class="mb-6">
                <p class="text-lg"><strong>Periode:</strong> {{ $validated['tanggal_mulai'] }} - {{ $validated['tanggal_selesai'] }}</p>
                <p class="text-2xl font-bold mt-2">Total Pendapatan: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full bg-white border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Booking ID</th>
                            <th class="px-4 py-2 text-left">User</th>
                            <th class="px-4 py-2 text-left">Lapangan</th>
                            <th class="px-4 py-2 text-left">Tanggal</th>
                            <th class="px-4 py-2 text-left">Total Harga</th>
                            <th class="px-4 py-2 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr class="border-t border-gray-200">
                                <td class="px-4 py-2">{{ $booking->id }}</td>
                                <td class="px-4 py-2">{{ $booking->user->name }}</td>
                                <td class="px-4 py-2">{{ $booking->lapangan->nama_lapangan }}</td>
                                <td class="px-4 py-2">{{ $booking->tanggal_main->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded text-xs font-semibold
                                        {{ $booking->status_booking === 'lunas' ? 'bg-green-100 text-green-800' : 
                                           'bg-yellow-100 text-yellow-800' }}">
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
</x-admin-layout>
