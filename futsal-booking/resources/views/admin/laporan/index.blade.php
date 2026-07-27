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

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .row-anim {
            opacity: 0;
            animation: fadeInUp 0.4s ease-out forwards;
        }
        .row-anim:hover { background: rgba(255,255,255,0.03); }

        .progress-ring { transform: rotate(-90deg); }

        input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            filter: invert(1);
            opacity: 1;
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23ffffff' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='m6 9 6 6 6-6'/%3E%3C/svg%3E") !important;
            background-position: right 0.75rem center !important;
            background-repeat: no-repeat !important;
            background-size: 1rem !important;
            padding-right: 2.5rem !important;
        }
    </style>

    <div class="max-w-5xl mx-auto px-6 md:px-10 py-10">

        <h1 class="text-3xl font-extrabold mb-6">Pendapatan Anda</h1>

        {{-- Card Pendapatan bulan ini — gaya Analytics Dashboard --}}
        <div class="bg-[#11151d] border border-white/10 rounded-2xl p-7 mb-8">

            <div class="mb-6">
                <h2 class="text-xl font-bold mb-1">Ringkasan Pendapatan</h2>
                <p class="text-slate-400 text-sm">Statistik performa bulan ini</p>
            </div>

            {{-- Pendapatan bulan ini --}}
            <div class="bg-[#0b0d12] border border-white/5 rounded-xl p-5 mb-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-white">Pendapatan bulan ini</span>
                </div>
                <p class="text-3xl font-extrabold mb-3 text-white">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                <div class="h-1.5 bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full w-0 bg-gradient-to-r from-sky-500 to-violet-500 rounded-full"></div>
                </div>
            </div>

            {{-- Mini stats --}}
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-[#0b0d12] border border-white/5 rounded-xl p-4">
                    <p class="text-xs text-slate-400 mb-1">Transaksi Berhasil</p>
                    <p class="text-lg font-bold">{{ $totalBooking }}</p>
                </div>
                <div class="bg-[#0b0d12] border border-white/5 rounded-xl p-4">
                    <p class="text-xs text-slate-400 mb-1">Total Booking</p>
                    <p class="text-lg font-bold">{{ $totalBooking }}</p>
                </div>
                <div class="bg-[#0b0d12] border border-white/5 rounded-xl p-4">
                    <p class="text-xs text-slate-400 mb-1">Paling Sering Dibooking</p>
                    <p class="text-base font-bold truncate text-white" title="{{ $lapanganPalingRamai }}">{{ $lapanganPalingRamai }}</p>
                </div>
            </div>

            <div class="flex gap-3 mt-6 no-print">
                <button onclick="document.getElementById('print-area').scrollIntoView({behavior: 'smooth'})" class="flex-1 rounded-lg bg-gradient-to-r from-sky-500 to-violet-500 hover:opacity-90 text-sm font-semibold py-2.5 transition">
                    Lihat Detail
                </button>
                <button onclick="window.print()" class="flex-1 rounded-lg border border-white/15 hover:bg-white/5 text-sm font-semibold py-2.5 transition">
                    Cetak / PDF
                </button>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-[#11151d] border border-white/5 rounded-xl p-6 mb-8 no-print">
            <h2 class="font-semibold mb-4">Filter Laporan</h2>
            <form action="{{ route('admin.laporan.index') }}" method="GET">
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm text-slate-400 mb-1.5">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="w-full bg-[#0b0d12] border border-white/10 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-violet-500">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-400 mb-1.5">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="w-full bg-[#0b0d12] border border-white/10 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-violet-500">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-400 mb-1.5">Lapangan</label>
                        <select name="lapangan_id" class="w-full bg-[#0b0d12] border border-white/10 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-violet-500">
                            <option value="" class="bg-[#0b0d12] text-white">Semua Lapangan</option>
                            @foreach($lapangans as $lapangan)
                                <option value="{{ $lapangan->id }}" {{ request('lapangan_id') == $lapangan->id ? 'selected' : '' }} class="bg-[#0b0d12] text-white">{{ $lapangan->nama_lapangan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-400 mb-1.5">Status</label>
                        <select name="status_booking" class="w-full bg-[#0b0d12] border border-white/10 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-violet-500">
                            <option value="" class="bg-[#0b0d12] text-white">Semua Status Valid</option>
                            <option value="dp_dibayar" {{ request('status_booking') == 'dp_dibayar' ? 'selected' : '' }} class="bg-[#0b0d12] text-white">DP Dibayar</option>
                            <option value="lunas" {{ request('status_booking') == 'lunas' ? 'selected' : '' }} class="bg-[#0b0d12] text-white">Lunas</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.laporan.index') }}" class="rounded-lg border border-white/15 hover:bg-white/5 text-sm font-medium px-4 py-2 transition">Reset</a>
                    <button type="submit" class="rounded-lg bg-amber-500 hover:bg-amber-400 text-black text-sm font-semibold px-4 py-2 transition">Filter</button>
                    <button type="button" onclick="window.print()" class="rounded-lg border border-white/15 hover:bg-white/5 text-sm font-medium px-4 py-2 transition">Cetak / PDF</button>
                </div>
            </form>
        </div>

        {{-- Print Area --}}
        <div id="print-area">
            <div class="print-header">
                <h1 style="font-size: 24px; font-weight: bold;">Laporan Penjualan Futsal</h1>
                <p>Periode: {{ request('tanggal_mulai') ? \Carbon\Carbon::parse(request('tanggal_mulai'))->format('d M Y') : 'Awal' }} s/d {{ request('tanggal_selesai') ? \Carbon\Carbon::parse(request('tanggal_selesai'))->format('d M Y') : 'Sekarang' }}</p>
                <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</p>
                <hr style="margin-top: 10px; margin-bottom: 20px; border: 1px solid #e5e7eb;">
            </div>

            {{-- Ringkasan Per Lapangan --}}
            <h2 class="font-semibold text-lg mb-3">Ringkasan Per Lapangan</h2>
            <div class="bg-[#11151d] border border-white/5 rounded-xl overflow-hidden mb-8">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-slate-400 border-b border-white/5">
                            <th class="px-6 py-3 font-medium">NAMA LAPANGAN</th>
                            <th class="px-6 py-3 font-medium">TOTAL BOOKING</th>
                            <th class="px-6 py-3 font-medium">TOTAL PENDAPATAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ringkasanLapangan as $i => $ringkasan)
                            <tr class="row-anim border-b border-white/5 last:border-0 transition-colors"
                                style="animation-delay: {{ $i * 60 }}ms">
                                <td class="px-6 py-4">{{ $ringkasan['nama_lapangan'] }}</td>
                                <td class="px-6 py-4">{{ $ringkasan['total_booking'] }}</td>
                                <td class="px-6 py-4">Rp {{ number_format($ringkasan['total_pendapatan'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-slate-500">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Detail Transaksi --}}
            <h2 class="font-semibold text-lg mb-3">Detail Transaksi</h2>
            <div class="bg-[#11151d] border border-white/5 rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-slate-400 border-b border-white/5">
                            <th class="px-6 py-3 font-medium">PENYEWA</th>
                            <th class="px-6 py-3 font-medium">LAPANGAN</th>
                            <th class="px-6 py-3 font-medium">TANGGAL</th>
                            <th class="px-6 py-3 font-medium">JAM</th>
                            <th class="px-6 py-3 font-medium">STATUS</th>
                            <th class="px-6 py-3 font-medium">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $i => $booking)
                            <tr class="row-anim border-b border-white/5 last:border-0 transition-colors"
                                style="animation-delay: {{ $i * 60 }}ms">
                                <td class="px-6 py-4">{{ $booking->user->name }}</td>
                                <td class="px-6 py-4">{{ $booking->lapangan->nama_lapangan }}</td>
                                <td class="px-6 py-4">{{ $booking->tanggal_main->format('d M Y') }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}</td>
                                <td class="px-6 py-4">
                                    @if($booking->status_booking == 'dp_dibayar')
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold border bg-amber-500/30 text-white border-amber-500/50">DP Dibayar</span>
                                    @elseif($booking->status_booking == 'lunas')
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold border bg-green-500/30 text-white border-green-500/50">Lunas</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold border bg-white/10 text-neutral-300 border-white/20">{{ ucfirst(str_replace('_', ' ', $booking->status_booking)) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-500">Tidak ada transaksi pada periode ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</x-admin-layout>
