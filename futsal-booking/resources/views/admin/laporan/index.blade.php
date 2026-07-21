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

    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-6">Pendapatan Anda</h1>

    <!-- Stat Cards -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-neutral-300 mb-4">Ringkasan Pendapatan</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl p-6">
                <p class="text-sm text-neutral-400 mb-1">Pendapatan bulan ini</p>
                <p class="text-3xl font-bold text-white">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl p-6">
                <p class="text-sm text-neutral-400 mb-1">Transaksi Berhasil</p>
                <p class="text-3xl font-bold text-white">{{ $totalBooking }}</p>
            </div>
        </div>
    </div>

    <!-- Filter Form (no-print) -->
    <div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl p-6 mb-6 no-print">
        <h3 class="text-lg font-semibold text-neutral-300 mb-4">Filter Laporan</h3>
        <form action="{{ route('admin.laporan.index') }}" method="GET">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-medium text-neutral-300 mb-2">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="w-full bg-white/5 border border-white/20 text-white rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                </div>
                <div>
                    <label for="tanggal_selesai" class="block text-sm font-medium text-neutral-300 mb-2">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="w-full bg-white/5 border border-white/20 text-white rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                </div>
                <div>
                    <label for="lapangan_id" class="block text-sm font-medium text-neutral-300 mb-2">Lapangan</label>
                    <select name="lapangan_id" id="lapangan_id" class="w-full bg-white/5 border border-white/20 text-white rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                        <option value="">Semua Lapangan</option>
                        @foreach($lapangans as $lapangan)
                            <option value="{{ $lapangan->id }}" {{ request('lapangan_id') == $lapangan->id ? 'selected' : '' }} class="text-neutral-900">{{ $lapangan->nama_lapangan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status_booking" class="block text-sm font-medium text-neutral-300 mb-2">Status</label>
                    <select name="status_booking" id="status_booking" class="w-full bg-white/5 border border-white/20 text-white rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                        <option value="">Semua Status Valid</option>
                        <option value="dp_dibayar" {{ request('status_booking') == 'dp_dibayar' ? 'selected' : '' }} class="text-neutral-900">DP Dibayar</option>
                        <option value="lunas" {{ request('status_booking') == 'lunas' ? 'selected' : '' }} class="text-neutral-900">Lunas</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-wrap justify-end gap-3">
                <a href="{{ route('admin.laporan.index') }}" class="px-4 py-2 border border-white/20 rounded-xl text-neutral-300 hover:bg-white/10 transition">Reset</a>
                <button type="submit" class="px-4 py-2 bg-amber-400 text-neutral-900 font-semibold rounded-xl hover:bg-amber-500 transition">Filter</button>
                <button type="button" onclick="window.print()" class="px-4 py-2 bg-white/20 text-white font-semibold rounded-xl hover:bg-white/30 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 0 002-2v-4a2 0 00-2-2H5a2 0 00-2 2v4a2 0 002 2h2m1 3h8a2 0 002-2V9a2 0 00-2-2h-1V6a2 0 00-2-2H8a2 0 00-2 2v1H5a2 0 00-2 2v6a2 0 002 2h1v2a2 0 002 2z"/>
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
            <h3 class="text-lg font-semibold text-neutral-300 mb-4">Ringkasan Per Lapangan</h3>
            <div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-white/20">
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-400">Nama Lapangan</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-neutral-400">Total Booking</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-neutral-400">Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @forelse($ringkasanLapangan as $ringkasan)
                                <tr class="hover:bg-white/5">
                                    <td class="px-6 py-3.5 text-white">{{ $ringkasan['nama_lapangan'] }}</td>
                                    <td class="px-6 py-3.5 text-center text-neutral-300">{{ $ringkasan['total_booking'] }}</td>
                                    <td class="px-6 py-3.5 text-right text-neutral-300">Rp {{ number_format($ringkasan['total_pendapatan'], 0, ',', '.') }}</td>
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
            <h3 class="text-lg font-semibold text-neutral-300 mb-4">Detail Transaksi</h3>
            <div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-white/20">
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-400">Penyewa</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-400">Lapangan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-400">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-400">Jam</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-400">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-neutral-400">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @forelse($bookings as $booking)
                                <tr class="hover:bg-white/5">
                                    <td class="px-6 py-3.5">
                                        <div class="font-medium text-white">{{ $booking->user->name }}</div>
                                    </td>
                                    <td class="px-6 py-3.5 text-neutral-300">{{ $booking->lapangan->nama_lapangan }}</td>
                                    <td class="px-6 py-3.5 text-neutral-400">{{ $booking->tanggal_main->format('d M Y') }}</td>
                                    <td class="px-6 py-3.5 text-neutral-400">{{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}</td>
                                    <td class="px-6 py-3.5">
                                        @if($booking->status_booking == 'dp_dibayar')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold border bg-amber-500/20 text-amber-300 border-amber-500/30">DP Dibayar</span>
                                        @elseif($booking->status_booking == 'lunas')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold border bg-green-500/20 text-green-300 border-green-500/30">Lunas</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold border bg-white/10 text-neutral-300 border-white/20">{{ ucfirst(str_replace('_', ' ', $booking->status_booking)) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5 text-right font-medium text-white">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
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
