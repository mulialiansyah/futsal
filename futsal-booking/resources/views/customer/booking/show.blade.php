@extends('layouts.customer')

@section('content')
    <a href="{{ route('customer.booking.index') }}" class="text-sm text-green-600 hover:underline mb-4 inline-block">← Kembali ke Booking</a>

    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-extrabold text-gray-800 mb-6">Detail Booking #{{ $booking->id }}</h2>

            <div class="space-y-4">
                <div class="flex justify-between border-b border-gray-100 pb-3">
                    <span class="text-gray-500">Lapangan</span>
                    <span class="font-semibold text-gray-800">{{ $booking->lapangan->nama_lapangan }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-3">
                    <span class="text-gray-500">Tanggal Main</span>
                    <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($booking->tanggal_main)->isoFormat('dddd, D MMMM YYYY') }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-3">
                    <span class="text-gray-500">Jam</span>
                    <span class="font-semibold text-gray-800">{{ substr($booking->jam_mulai, 0, 5) }} – {{ substr($booking->jam_selesai, 0, 5) }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-3">
                    <span class="text-gray-500">Total Harga</span>
                    <span class="font-extrabold text-gray-800 text-lg">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-3">
                    <span class="text-gray-500">DP (50%)</span>
                    <span class="font-extrabold text-green-600 text-lg">Rp {{ number_format($booking->total_harga / 2, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between pb-3">
                    <span class="text-gray-500">Status Booking</span>
                    @php
                        $colors = ['pending'=>'bg-yellow-100 text-yellow-700','dp_dibayar'=>'bg-blue-100 text-blue-700','lunas'=>'bg-green-100 text-green-700','batal'=>'bg-red-100 text-red-700'];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $colors[$booking->status_booking] ?? '' }}">
                        {{ ucfirst(str_replace('_', ' ', $booking->status_booking)) }}
                    </span>
                </div>
            </div>

            <!-- Pembayaran Info -->
            @if($booking->pembayaran)
                <div class="mt-6 bg-gray-50 rounded-xl p-4 border">
                    <h4 class="font-bold text-gray-700 mb-3">Info Pembayaran DP</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Nominal DP</span>
                            <span class="font-semibold">Rp {{ number_format($booking->pembayaran->nominal_dp, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Status Verifikasi</span>
                            @php $vc = ['menunggu'=>'bg-yellow-100 text-yellow-700','disetujui'=>'bg-green-100 text-green-700','ditolak'=>'bg-red-100 text-red-700']; @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $vc[$booking->pembayaran->status_verifikasi] ?? '' }}">
                                {{ ucfirst($booking->pembayaran->status_verifikasi) }}
                            </span>
                        </div>
                        @if($booking->pembayaran->bukti_transfer)
                            <div class="mt-3">
                                <p class="text-gray-500 text-sm mb-2">Bukti Transfer:</p>
                                <img src="{{ asset('storage/'.$booking->pembayaran->bukti_transfer) }}"
                                     alt="Bukti Transfer" class="rounded-lg shadow max-w-xs border">
                            </div>
                        @endif
                    </div>
                </div>
            @else
                @if($booking->status_booking === 'pending')
                <div class="mt-6">
                    <a href="{{ route('customer.pembayaran.create', $booking) }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-lg transition w-full block text-center text-sm">
                        Upload Bukti Transfer DP →
                    </a>
                </div>
                @endif
            @endif
        </div>
    </div>
@endsection
