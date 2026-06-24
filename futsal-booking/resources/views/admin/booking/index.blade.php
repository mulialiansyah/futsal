@extends('layouts.admin')

@section('title', 'Semua Booking')

@section('content')
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-700 text-lg">Daftar Semua Booking</h3>
        </div>
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">Pelanggan</th>
                    <th class="px-6 py-3 text-left">Lapangan</th>
                    <th class="px-6 py-3 text-left">Tanggal Main</th>
                    <th class="px-6 py-3 text-left">Jam</th>
                    <th class="px-6 py-3 text-left">Total</th>
                    <th class="px-6 py-3 text-left">DP</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $booking->user->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $booking->lapangan->nama_lapangan }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($booking->tanggal_main)->isoFormat('D MMM YYYY') }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ substr($booking->jam_mulai, 0, 5) }} – {{ substr($booking->jam_selesai, 0, 5) }}</td>
                    <td class="px-6 py-4 font-semibold">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-gray-600">Rp {{ number_format($booking->total_harga / 2, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @php
                            $colors = ['pending'=>'bg-yellow-100 text-yellow-700','dp_dibayar'=>'bg-blue-100 text-blue-700','lunas'=>'bg-green-100 text-green-700','batal'=>'bg-red-100 text-red-700'];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $colors[$booking->status_booking] ?? '' }}">
                            {{ ucfirst(str_replace('_', ' ', $booking->status_booking)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <form method="POST" action="{{ route('admin.bookings.updateStatus', $booking) }}" class="flex items-center justify-center gap-2">
                            @csrf @method('PATCH')
                            <select name="status_booking" class="text-xs border border-gray-300 rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-green-400">
                                @foreach(['pending','dp_dibayar','lunas','batal'] as $status)
                                    <option value="{{ $status }}" {{ $booking->status_booking === $status ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="bg-green-600 text-white text-xs px-3 py-1 rounded-lg hover:bg-green-700 transition">Simpan</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-6 py-8 text-center text-gray-400">Belum ada data booking.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
@endsection
