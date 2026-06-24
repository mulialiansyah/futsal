@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow p-6 flex items-center gap-4 border-l-4 border-green-500">
            <div class="bg-green-100 p-3 rounded-full">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Lapangan</p>
                <p class="text-3xl font-extrabold text-gray-800">{{ $totalLapangan }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 flex items-center gap-4 border-l-4 border-blue-500">
            <div class="bg-blue-100 p-3 rounded-full">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Booking</p>
                <p class="text-3xl font-extrabold text-gray-800">{{ $totalBooking }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 flex items-center gap-4 border-l-4 border-yellow-500">
            <div class="bg-yellow-100 p-3 rounded-full">
                <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Verifikasi Pending</p>
                <p class="text-3xl font-extrabold text-gray-800">{{ $pendingVerifikasi }}</p>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-700 text-lg">Booking Terbaru</h3>
            <a href="{{ route('admin.bookings.index') }}" class="text-sm text-green-600 hover:underline font-medium">Lihat Semua →</a>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">Pelanggan</th>
                    <th class="px-6 py-3 text-left">Lapangan</th>
                    <th class="px-6 py-3 text-left">Tanggal Main</th>
                    <th class="px-6 py-3 text-left">Total Harga</th>
                    <th class="px-6 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($bookingTerbaru as $booking)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $booking->user->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $booking->lapangan->nama_lapangan }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($booking->tanggal_main)->isoFormat('D MMM YYYY') }}</td>
                    <td class="px-6 py-4 text-gray-800 font-semibold">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @php
                            $colors = [
                                'pending'    => 'bg-yellow-100 text-yellow-700',
                                'dp_dibayar' => 'bg-blue-100 text-blue-700',
                                'lunas'      => 'bg-green-100 text-green-700',
                                'batal'      => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $colors[$booking->status_booking] ?? '' }}">
                            {{ ucfirst(str_replace('_', ' ', $booking->status_booking)) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada booking.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
