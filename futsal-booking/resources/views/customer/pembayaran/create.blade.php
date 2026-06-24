@extends('layouts.customer')

@section('content')
    <a href="{{ route('customer.booking.show', $booking) }}" class="text-sm text-green-600 hover:underline mb-4 inline-block">← Kembali ke Detail Booking</a>

    <div class="max-w-lg">
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-extrabold text-gray-800 mb-2">Upload Bukti Transfer DP</h2>
            <p class="text-gray-500 text-sm mb-6">Booking #{{ $booking->id }} – {{ $booking->lapangan->nama_lapangan }}</p>

            <!-- DP Summary -->
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600">Total Harga Sewa:</span>
                    <span class="font-bold text-gray-800">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center border-t border-green-200 pt-2">
                    <span class="text-sm text-gray-600">Nominal DP (50%):</span>
                    <span class="text-xl font-extrabold text-green-600">Rp {{ number_format($nominal_dp, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Transfer Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                <p class="text-sm font-semibold text-blue-800 mb-1">Transfer ke Rekening:</p>
                <p class="text-sm text-blue-700">Bank BCA: <span class="font-bold">1234567890</span></p>
                <p class="text-sm text-blue-700">a.n. <span class="font-bold">FutsalPro Arena</span></p>
            </div>

            <form method="POST" action="{{ route('customer.pembayaran.store', $booking) }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Foto Struk Transfer</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-green-400 transition">
                        <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <input type="file" name="bukti_transfer" accept="image/*"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                        <p class="text-gray-400 text-xs mt-2">Format: JPG, PNG (Maks. 2MB)</p>
                    </div>
                    @error('bukti_transfer')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-3 rounded-lg transition w-full text-sm">
                    Kirim Bukti Transfer
                </button>
            </form>
        </div>
    </div>
@endsection
