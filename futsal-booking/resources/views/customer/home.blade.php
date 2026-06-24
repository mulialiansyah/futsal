@extends('layouts.customer')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-extrabold text-gray-800">Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
        <p class="text-gray-500 mt-1">Kelola booking lapangan futsal kamu di sini.</p>
    </div>

    <!-- Quick Action -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('customer.booking.create') }}"
           class="bg-gradient-to-br from-green-500 to-green-700 text-white rounded-xl shadow-lg p-6 hover:shadow-xl hover:scale-[1.02] transition transform">
            <div class="flex items-center gap-4">
                <div class="bg-white/20 p-3 rounded-full">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </div>
                <div>
                    <p class="font-bold text-lg">Booking Sekarang</p>
                    <p class="text-green-100 text-sm">Pesan lapangan futsal</p>
                </div>
            </div>
        </a>

        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
            <p class="text-gray-500 text-sm">Total Booking Kamu</p>
            <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $myBookings->count() }}</p>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-yellow-500">
            <p class="text-gray-500 text-sm">Menunggu Pembayaran</p>
            <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $myBookings->where('status_booking', 'pending')->count() }}</p>
        </div>
    </div>

    <!-- My Bookings -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-700 text-lg">Riwayat Booking</h3>
            <a href="{{ route('customer.booking.index') }}" class="text-sm text-green-600 hover:underline font-medium">Lihat Semua →</a>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">Lapangan</th>
                    <th class="px-6 py-3 text-left">Tanggal Main</th>
                    <th class="px-6 py-3 text-left">Jam</th>
                    <th class="px-6 py-3 text-left">Total Harga</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($myBookings->take(5) as $booking)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $booking->lapangan->nama_lapangan }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($booking->tanggal_main)->isoFormat('D MMM YYYY') }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ substr($booking->jam_mulai, 0, 5) }} – {{ substr($booking->jam_selesai, 0, 5) }}</td>
                    <td class="px-6 py-4 font-semibold">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @php
                            $colors = ['pending'=>'bg-yellow-100 text-yellow-700','dp_dibayar'=>'bg-blue-100 text-blue-700','lunas'=>'bg-green-100 text-green-700','batal'=>'bg-red-100 text-red-700'];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $colors[$booking->status_booking] ?? '' }}">
                            {{ ucfirst(str_replace('_', ' ', $booking->status_booking)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('customer.booking.show', $booking) }}"
                           class="text-green-600 hover:underline text-xs font-semibold">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">Belum ada booking. Yuk pesan lapangan!</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
