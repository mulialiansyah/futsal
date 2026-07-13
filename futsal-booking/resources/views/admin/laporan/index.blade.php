<x-admin-layout>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #print-area, #print-area * {
                visibility: visible;
            }
            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
            .print-header {
                display: block !important;
                text-align: center;
                margin-bottom: 20px;
            }
        }
        .print-header {
            display: none;
        }
    </style>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 no-print">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-6">Filter Laporan Penjualan</h1>

            <form action="{{ route('admin.laporan.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label for="lapangan_id" class="block text-sm font-medium text-gray-700 mb-2">Lapangan</label>
                        <select name="lapangan_id" id="lapangan_id" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            <option value="">Semua Lapangan</option>
                            @foreach($lapangans as $lapangan)
                                <option value="{{ $lapangan->id }}" {{ request('lapangan_id') == $lapangan->id ? 'selected' : '' }}>{{ $lapangan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="status_booking" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status_booking" id="status_booking" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            <option value="">Semua Status Valid</option>
                            <option value="dp_dibayar" {{ request('status_booking') == 'dp_dibayar' ? 'selected' : '' }}>DP Dibayar</option>
                            <option value="lunas" {{ request('status_booking') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('admin.laporan.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 flex items-center">Reset</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Filter</button>
                    <button type="button" onclick="window.print()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                        </svg>
                        Cetak / PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="print-area">
        <div class="print-header">
            <h1 style="font-size: 24px; font-weight: bold;">Laporan Penjualan Futsal</h1>
            <p>Periode: {{ request('tanggal_mulai') ? \Carbon\Carbon::parse(request('tanggal_mulai'))->format('d M Y') : 'Awal' }} s/d {{ request('tanggal_selesai') ? \Carbon\Carbon::parse(request('tanggal_selesai'))->format('d M Y') : 'Sekarang' }}</p>
            <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</p>
            <hr style="margin-top: 10px; margin-bottom: 20px; border: 1px solid black;">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <h3 class="text-gray-500 text-sm font-medium">Total Booking</h3>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalBooking }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <h3 class="text-gray-500 text-sm font-medium">Total Pendapatan</h3>
                <p class="text-2xl font-bold text-green-600 mt-2">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <h3 class="text-gray-500 text-sm font-medium">Rata-rata / Booking</h3>
                <p class="text-2xl font-bold text-blue-600 mt-2">Rp {{ number_format($rataRataPerBooking, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <h3 class="text-gray-500 text-sm font-medium">Lapangan Paling Ramai</h3>
                <p class="text-xl font-bold text-orange-600 mt-2">{{ $lapanganPalingRamai }}</p>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 text-gray-900">
                <h2 class="text-xl font-bold mb-4">Ringkasan Per Lapangan</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="border-b px-4 py-3 font-medium text-gray-600">Nama Lapangan</th>
                                <th class="border-b px-4 py-3 font-medium text-gray-600 text-center">Total Booking</th>
                                <th class="border-b px-4 py-3 font-medium text-gray-600 text-right">Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ringkasanLapangan as $ringkasan)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="border-b px-4 py-3">{{ $ringkasan['nama_lapangan'] }}</td>
                                    <td class="border-b px-4 py-3 text-center">{{ $ringkasan['total_booking'] }}</td>
                                    <td class="border-b px-4 py-3 text-right">Rp {{ number_format($ringkasan['total_pendapatan'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="border-b px-4 py-3 text-center text-gray-500">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-xl font-bold mb-4">Detail Transaksi</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="border-b px-4 py-3 font-medium text-gray-600">Penyewa</th>
                                <th class="border-b px-4 py-3 font-medium text-gray-600">Lapangan</th>
                                <th class="border-b px-4 py-3 font-medium text-gray-600">Tanggal</th>
                                <th class="border-b px-4 py-3 font-medium text-gray-600">Jam</th>
                                <th class="border-b px-4 py-3 font-medium text-gray-600">Status</th>
                                <th class="border-b px-4 py-3 font-medium text-gray-600 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="border-b px-4 py-3">
                                        <div class="font-medium text-gray-900">{{ $booking->user->name }}</div>
                                    </td>
                                    <td class="border-b px-4 py-3">{{ $booking->lapangan->nama }}</td>
                                    <td class="border-b px-4 py-3">{{ $booking->tanggal_main->format('d M Y') }}</td>
                                    <td class="border-b px-4 py-3">{{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}</td>
                                    <td class="border-b px-4 py-3">
                                        @if($booking->status_booking == 'dp_dibayar')
                                            <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded border border-orange-200">DP Dibayar</span>
                                        @elseif($booking->status_booking == 'lunas')
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-200">Lunas</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-200">{{ ucfirst(str_replace('_', ' ', $booking->status_booking)) }}</span>
                                        @endif
                                    </td>
                                    <td class="border-b px-4 py-3 text-right font-medium">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="border-b px-4 py-3 text-center text-gray-500">Tidak ada transaksi pada periode ini</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
