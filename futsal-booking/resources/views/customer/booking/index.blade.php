@extends('layouts.customer')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-extrabold text-gray-800">Booking Saya</h2>
        <a href="{{ route('customer.booking.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg transition">
            + Booking Baru
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">No</th>
                    <th class="px-6 py-3 text-left">Lapangan</th>
                    <th class="px-6 py-3 text-left">Tanggal Main</th>
                    <th class="px-6 py-3 text-left">Jam</th>
                    <th class="px-6 py-3 text-left">Total Harga</th>
                    <th class="px-6 py-3 text-left">DP (50%)</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bookings as $i => $booking)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-gray-500">{{ $i + 1 }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $booking->lapangan->nama_lapangan }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($booking->tanggal_main)->isoFormat('D MMM YYYY') }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ substr($booking->jam_mulai, 0, 5) }} – {{ substr($booking->jam_selesai, 0, 5) }}</td>
                    <td class="px-6 py-4 font-semibold">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-green-700 font-semibold">Rp {{ number_format($booking->total_harga / 2, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @php
                            $colors = ['pending'=>'bg-yellow-100 text-yellow-700','dp_dibayar'=>'bg-blue-100 text-blue-700','lunas'=>'bg-green-100 text-green-700','batal'=>'bg-red-100 text-red-700'];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $colors[$booking->status_booking] ?? '' }}">
                            {{ ucfirst(str_replace('_', ' ', $booking->status_booking)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('customer.booking.show', $booking) }}"
                               class="bg-green-100 text-green-700 hover:bg-green-200 px-3 py-1.5 rounded-lg text-xs font-semibold transition">Detail</a>

                            @if($booking->status_booking === 'pending' && !$booking->pembayaran)
                                <a href="{{ route('customer.pembayaran.create', $booking) }}"
                                   class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1.5 rounded-lg text-xs font-semibold transition">Bayar DP</a>
                            @endif

                            @if($booking->status_booking === 'pending')
                                <form method="POST" action="{{ route('customer.booking.destroy', $booking) }}"
                                      onsubmit="return confirm('Yakin batalkan booking ini?')">
                                    @csrf @method('DELETE')
                                    <button class="bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1.5 rounded-lg text-xs font-semibold transition">Batal</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-6 py-8 text-center text-gray-400">Belum ada booking.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
@endsection
