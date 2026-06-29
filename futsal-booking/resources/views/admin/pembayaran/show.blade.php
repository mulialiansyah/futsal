<x-admin-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Detail Pembayaran</h1>
                <a href="{{ route('admin.pembayaran.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">Kembali</a>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Booking ID</label>
                <p class="text-lg">{{ $pembayaran->booking_id }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                <p class="text-lg">{{ $pembayaran->booking->user->name }} ({{ $pembayaran->booking->user->email }})</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nominal</label>
                <p class="text-lg">Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Transfer</label>
                @if($pembayaran->bukti_transfer)
                    <img src="{{ Storage::url($pembayaran->bukti_transfer) }}" alt="Bukti Transfer" class="w-64 h-64 object-cover">
                @else
                    <span class="text-gray-400">No Bukti</span>
                @endif
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Verifikasi</label>
                <p class="text-lg">
                    <span class="px-2 py-1 rounded text-xs font-semibold
                        {{ $pembayaran->status_verifikasi === 'diterima' ? 'bg-green-100 text-green-800' : 
                           ($pembayaran->status_verifikasi === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                           'bg-red-100 text-red-800') }}">
                        {{ ucfirst($pembayaran->status_verifikasi) }}
                    </span>
                </p>
            </div>

            @if($pembayaran->status_verifikasi === 'pending')
                <div class="mt-6">
                    <h2 class="text-xl font-semibold mb-4">Verifikasi Pembayaran</h2>
                    <form action="{{ route('admin.pembayaran.verify', $pembayaran) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="flex gap-4">
                            <button type="submit" name="status_verifikasi" value="diterima" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Terima</button>
                            <button type="submit" name="status_verifikasi" value="ditolak" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Tolak</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
