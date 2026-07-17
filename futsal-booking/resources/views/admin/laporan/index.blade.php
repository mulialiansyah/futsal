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

    <h1 class="text-2xl sm:text-3xl font-bold text-neutral-900 mb-6">Pendapatan Anda</h1>

    <!-- Stat Cards -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-neutral-800 mb-4">Ringkasan Pendapatan</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white rounded-2xl card-shadow p-6">
                <p class="text-sm text-neutral-600 mb-1">Pendapatan bulan ini</p>
                <p class="text-3xl font-bold text-neutral-900">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-white rounded-2xl card-shadow p-6">
                <p class="text-sm text-neutral-600 mb-1">Transaksi Berhasil</p>
                <p class="text-3xl font-bold text-neutral-900">{{ $totalBooking }}</p>
            </div>
        </div>
    </div>

    <!-- Filter Form (no-print) -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 no-print">
        <h3 class="text-lg font-semibold text-neutral-800 mb-4">Filter Laporan</h3>
        <form action="{{ route('admin.laporan.index') }}" method="GET">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-medium text-neutral-700 mb-2">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="w-full border border-neutral-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                </div>
                <div>
                    <label for="tanggal_selesai" class="block text-sm font-medium text-neutral-700 mb-2">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="w-full border border-neutral-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                </div>
                <div>
                    <label for="lapangan_id" class="block text-sm font-medium text-neutral-700 mb-2">Lapangan</label>
                    <select name="lapangan_id" id="lapangan_id" class="w-full border border-neutral-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                        <option value="">Semua Lapangan</option>
                        @foreach($lapangans as $lapangan)
                            <option value="{{ $lapangan->id }}" {{ request('lapangan_id') == $lapangan->id ? 'selected' : '' }}>{{ $lapangan->nama_lapangan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status_booking" class="block text-sm font-medium text-neutral-700 mb-2">Status</label>
                    <select name="status_booking" id="status_booking" class="w-full border border-neutral-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                        <option value="">Semua Status Valid</option>
                        <option value="dp_dibayar" {{ request('status_booking') == 'dp_dibayar' ? 'selected' : '' }}>DP Dibayar</option>
                        <option value="lunas" {{ request('status_booking') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-wrap justify-end gap-3">
                <a href="{{ route('admin.laporan.index') }}" class="px-4 py-2 border border-neutral-300 rounded-xl text-neutral-700 hover:bg-neutral-50 transition">Reset</a>
                <button type="submit" class="px-4 py-2 bg-yellow-400 text-neutral-900 font-semibold rounded-xl hover:bg-yellow-500 transition">Filter</button>
                <button type="button" onclick="window.print()" class="px-4 py-2 bg-neutral-900 text-white font-semibold rounded-xl hover:bg-neutral-800 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m1 3h8a2 2 0 002-2V9a2 2 0 00-2-2h-1V6a2 2 0 00-2-2H8a2 2 0 00-2 2v1H5a2 2 0 00-2 2v6a2 2 0 002 2h1v2a2 2 0 002 2z"/>
                    </svg>
                    Cetak / PDF
                </button>
            </div>
        </form>
    </div>

    <!-- Ringkasan Per Lapangan -->
    <div id="print-area">
        <div class="print-header">
            <h1 style="font-size: 24px; font-weight: bold;">Laporan Penjualan Futsal</h1>
            <p>Periode: {{ request('tanggal_mulai') ? \Carbon\Carbon::parse(request('tanggal_mulai'))->format('d M Y') : 'Awal' }} s/d {{ request('tanggal_selesai') ? \Carbon\Carbon::parse(request('tanggal_selesai'))->format('d M Y') : 'Sekarang' }}</p>
            <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</p>
            <hr style="margin-top: 10px; margin-bottom: 20px; border: 1px solid #e5e7eb;">
        </div>

        <div class="mb-8">
            <h3 class="text-lg font-semibold text-neutral-800 mb-4">Ringkasan Per Lapangan</h3>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-neutral-200">
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Nama Lapangan</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-neutral-600">Total Booking</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-neutral-600">Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100">
                            @forelse($ringkasanLapangan as $ringkasan)
                                <tr class="hover:bg-neutral-50">
                                    <td class="px-6 py-3.5 text-neutral-900">{{ $ringkasan['nama_lapangan'] }}</td>
                                    <td class="px-6 py-3.5 text-center text-neutral-700">{{ $ringkasan['total_booking'] }}</td>
                                    <td class="px-6 py-3.5 text-right text-neutral-700">Rp {{ number_format($ringkasan['total_pendapatan'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-neutral-500">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Detail Transaksi -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-neutral-800 mb-4">Detail Transaksi</h3>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-neutral-200">
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Penyewa</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Lapangan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Jam</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-neutral-600">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100">
                            @forelse($bookings as $booking)
                                <tr class="hover:bg-neutral-50">
                                    <td class="px-6 py-3.5">
                                        <div class="font-medium text-neutral-900">{{ $booking->user->name }}</div>
                                    </td>
                                    <td class="px-6 py-3.5 text-neutral-700">{{ $booking->lapangan->nama_lapangan }}</td>
                                    <td class="px-6 py-3.5 text-neutral-600">{{ $booking->tanggal_main->format('d M Y') }}</td>
                                    <td class="px-6 py-3.5 text-neutral-600">{{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}</td>
                                    <td class="px-6 py-3.5">
                                        @if($booking->status_booking == 'dp_dibayar')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold border bg-amber-100 text-amber-700 border-amber-200">DP Dibayar</span>
                                        @elseif($booking->status_booking == 'lunas')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold border bg-green-100 text-green-700 border-green-200">Lunas</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold border bg-neutral-100 text-neutral-700 border-neutral-200">{{ ucfirst(str_replace('_', ' ', $booking->status_booking)) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5 text-right font-medium text-neutral-900">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-neutral-500">Tidak ada transaksi pada periode ini</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-admin-layout>
