<x-admin-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Detail Booking</h1>
                <a href="{{ route('admin.booking.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">Kembali</a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

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
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'dp_dibayar' => 'bg-blue-100 text-blue-800',
                            'lunas' => 'bg-green-100 text-green-800',
                            'expired' => 'bg-red-100 text-red-800',
                            'batal' => 'bg-gray-100 text-gray-800',
                        ];
                    @endphp
                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $statusColors[$booking->status_booking] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst(str_replace('_', ' ', $booking->status_booking)) }}
                    </span>
                </p>
            </div>

            {{-- Ringkasan Pembayaran --}}
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="font-bold text-gray-700 mb-3">Ringkasan Pembayaran</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Harga</span>
                        <span class="font-bold">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Dibayar</span>
                        <span class="font-bold text-green-600">Rp {{ number_format($booking->total_dibayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <span class="font-bold text-gray-700">Sisa Tagihan</span>
                        <span class="font-extrabold {{ $booking->sisa_tagihan > 0 ? 'text-red-600' : 'text-green-600' }}">
                            Rp {{ number_format($booking->sisa_tagihan, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Tombol Konfirmasi Pelunasan Cash --}}
            @if($booking->status_booking === 'dp_dibayar' && $booking->sisa_tagihan > 0)
                <div class="bg-orange-50 border border-orange-300 rounded-xl p-5 mb-6">
                    <h3 class="font-bold text-orange-800 mb-2">💵 Konfirmasi Pelunasan Cash</h3>
                    <p class="text-sm text-orange-700 mb-3">
                        Customer bayar sisa tagihan langsung di tempat (cash)? Klik tombol di bawah untuk konfirmasi.
                    </p>
                    <form action="{{ route('admin.pembayaran.confirm-cash', $booking) }}" method="POST"
                          onsubmit="return confirm('Yakin konfirmasi pelunasan cash Rp {{ number_format($booking->sisa_tagihan, 0, ',', '.') }}?');">
                        @csrf
                        <input type="hidden" name="nominal" value="{{ $booking->sisa_tagihan }}">
                        <button type="submit"
                                class="bg-orange-600 hover:bg-orange-700 text-white font-bold px-6 py-3 rounded-lg transition w-full">
                            💵 Konfirmasi Pelunasan Cash — Rp {{ number_format($booking->sisa_tagihan, 0, ',', '.') }}
                        </button>
                    </form>
                </div>
            @endif

            {{-- Ubah Status Manual --}}
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

            {{-- Riwayat Pembayaran --}}
            <div class="mt-6">
                <h2 class="text-xl font-semibold mb-4">Riwayat Pembayaran</h2>

                @if($booking->pembayarans->isEmpty())
                    <p class="text-gray-400 text-sm">Belum ada pembayaran.</p>
                @else
                    <div class="space-y-3">
                        @foreach($booking->pembayarans as $idx => $payment)
                            <div class="border rounded-lg p-4 {{ $payment->status_verifikasi === 'diterima' ? 'border-green-200 bg-green-50' : ($payment->status_verifikasi === 'ditolak' ? 'border-red-200 bg-red-50' : 'border-gray-200') }}">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-xs text-gray-500">
                                            Pembayaran #{{ $idx + 1 }} — {{ $payment->created_at->format('d M Y H:i') }}
                                            @if(!$payment->bukti_transfer)
                                                <span class="ml-2 bg-orange-100 text-orange-700 px-2 py-0.5 rounded text-xs font-bold">CASH</span>
                                            @endif
                                        </p>
                                        <p class="font-bold text-gray-800 text-lg">Rp {{ number_format($payment->nominal, 0, ',', '.') }}</p>
                                    </div>
                                    @php
                                        $verifikasi = [
                                            'pending'  => 'bg-yellow-100 text-yellow-700',
                                            'diterima' => 'bg-green-100 text-green-700',
                                            'ditolak'  => 'bg-red-100 text-red-700',
                                        ];
                                        $labelV = [
                                            'pending'  => '⏳ Pending',
                                            'diterima' => '✅ Diterima',
                                            'ditolak'  => '❌ Ditolak',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $verifikasi[$payment->status_verifikasi] ?? 'bg-gray-100' }}">
                                        {{ $labelV[$payment->status_verifikasi] ?? $payment->status_verifikasi }}
                                    </span>
                                </div>

                                @if($payment->bukti_transfer)
                                    <div class="mt-2">
                                        <img src="{{ Storage::url($payment->bukti_transfer) }}" alt="Bukti Transfer" class="w-32 h-32 object-cover rounded-lg border">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
