<x-admin-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-6">Daftar Pembayaran</h1>

            <div class="overflow-x-auto">
                <table class="w-full bg-white border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Booking ID</th>
                            <th class="px-4 py-2 text-left">User</th>
                            <th class="px-4 py-2 text-left">Nominal</th>
                            <th class="px-4 py-2 text-left">Status Verifikasi</th>
                            <th class="px-4 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pembayarans as $pembayaran)
                            <tr class="border-t border-gray-200">
                                <td class="px-4 py-2">{{ $pembayaran->booking_id }}</td>
                                <td class="px-4 py-2">{{ $pembayaran->booking->user->name }}</td>
                                <td class="px-4 py-2">Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded text-xs font-semibold
                                        {{ $pembayaran->status_verifikasi === 'diterima' ? 'bg-green-100 text-green-800' : 
                                           ($pembayaran->status_verifikasi === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($pembayaran->status_verifikasi) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('admin.pembayaran.show', $pembayaran) }}" class="text-blue-600 hover:underline">Lihat</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
