<x-admin-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Detail Booking</h1>
                <a href="{{ route('admin.booking.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">Kembali</a>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                <p class="text-lg">{{ $booking->user->name }} ({{ $booking->user->email }})</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Lapangan</label>
                <p class="text-lg">{{ $booking->lapangan->nama_lapangan }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Main</label>
                <p class="text-lg">{{ $booking->tanggal_main->format('d/m/Y') }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jam</label>
                <p class="text-lg">{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Total Harga</label>
                <p class="text-lg">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Booking</label>
                <p class="text-lg">
                    <span class="px-2 py-1 rounded text-xs font-semibold
                                        {{ $booking->status_booking === 'lunas' ? 'bg-green-100 text-green-800' : 
                                           ($booking->status_booking === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($booking->status_booking === 'batal' ? 'bg-red-100 text-red-800' : 
                                           'bg-gray-100 text-gray-800')) }}">
                                        {{ ucfirst($booking->status_booking) }}
                    </span>
                </p>
            </div>

            @if($booking->status_booking !== 'lunas' && $booking->status_booking !== 'batal' && $booking->status_booking !== 'expired')
                <div class="mt-6">
                    <h2 class="text-xl font-semibold mb-4">Ubah Status</h2>
                    <form action="{{ route('admin.booking.update-status', $booking) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <select name="status_booking" id="status_booking" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                                <option value="pending" {{ $booking->status_booking === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="dp_dibayar" {{ $booking->status_booking === 'dp_dibayar' ? 'selected' : '' }}>DP Dibayar</option>
                                <option value="lunas" {{ $booking->status_booking === 'lunas' ? 'selected' : '' }}>Lunas</option>
                                <option value="batal" {{ $booking->status_booking === 'batal' ? 'selected' : '' }}>Batal</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Update Status</button>
                    </form>
                </div>
            @endif

            @if($booking->pembayaran)
                <div class="mt-6">
                    <h2 class="text-xl font-semibold mb-4">Pembayaran</h2>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nominal</label>
                        <p class="text-lg">Rp {{ number_format($booking->pembayaran->nominal, 0, ',', '.') }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Transfer</label>
                        @if($booking->pembayaran->bukti_transfer)
                            <img src="{{ Storage::url($booking->pembayaran->bukti_transfer) }}" alt="Bukti Transfer" class="w-64 h-64 object-cover">
                        @else
                            <span class="text-gray-400">No Bukti</span>
                        @endif
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Verifikasi</label>
                        <p class="text-lg">{{ ucfirst($booking->pembayaran->status_verifikasi) }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
