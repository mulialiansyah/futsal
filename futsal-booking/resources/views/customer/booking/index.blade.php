<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Riwayat Booking Saya
            </h2>
            <a href="{{ route('customer.booking.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm">
                + Booking Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($bookings->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500">
                    Belum ada riwayat booking. Yuk mulai main futsal!
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lapangan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal & Waktu</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($bookings as $booking)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="font-medium text-gray-900">{{ $booking->lapangan->nama_lapangan }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($booking->tanggal_main)->isoFormat('D MMM YYYY') }}</div>
                                                <div class="text-sm text-gray-500">{{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $colors = [
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'dp_dibayar' => 'bg-blue-100 text-blue-800',
                                                        'lunas' => 'bg-green-100 text-green-800',
                                                        'expired' => 'bg-red-100 text-red-800',
                                                        'batal' => 'bg-gray-100 text-gray-800'
                                                    ];
                                                    $color = $colors[$booking->status_booking] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                                    {{ ucfirst(str_replace('_', ' ', $booking->status_booking)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('customer.booking.show', $booking) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>