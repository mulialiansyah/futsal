
&lt;x-admin-layout&gt;

    &lt;style&gt;
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
    &lt;/style&gt;

    &lt;h1 class="text-2xl sm:text-3xl font-bold text-white mb-6"&gt;Pendapatan Anda&lt;/h1&gt;

    &lt;!-- Stat Cards --&gt;
    &lt;div class="mb-8"&gt;
        &lt;h2 class="text-lg font-semibold text-neutral-300 mb-4"&gt;Ringkasan Pendapatan&lt;/h2&gt;
        &lt;div class="grid grid-cols-1 sm:grid-cols-2 gap-4"&gt;
            &lt;div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl p-6"&gt;
                &lt;p class="text-sm text-neutral-400 mb-1"&gt;Pendapatan bulan ini&lt;/p&gt;
                &lt;p class="text-3xl font-bold text-white"&gt;
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                &lt;/p&gt;
            &lt;/div&gt;
            &lt;div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl p-6"&gt;
                &lt;p class="text-sm text-neutral-400 mb-1"&gt;Transaksi Berhasil&lt;/p&gt;
                &lt;p class="text-3xl font-bold text-white"&gt;{{ $totalBooking }}&lt;/p&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;!-- Filter Form (no-print) --&gt;
    &lt;div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl p-6 mb-6 no-print"&gt;
        &lt;h3 class="text-lg font-semibold text-neutral-300 mb-4"&gt;Filter Laporan&lt;/h3&gt;
        &lt;form action="{{ route('admin.laporan.index') }}" method="GET"&gt;
            &lt;div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4"&gt;
                &lt;div&gt;
                    &lt;label for="tanggal_mulai" class="block text-sm font-medium text-neutral-300 mb-2"&gt;Tanggal Mulai&lt;/label&gt;
                    &lt;input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="w-full bg-white/5 border border-white/20 text-white rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent"&gt;
                &lt;/div&gt;
                &lt;div&gt;
                    &lt;label for="tanggal_selesai" class="block text-sm font-medium text-neutral-300 mb-2"&gt;Tanggal Selesai&lt;/label&gt;
                    &lt;input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="w-full bg-white/5 border border-white/20 text-white rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent"&gt;
                &lt;/div&gt;
                &lt;div&gt;
                    &lt;label for="lapangan_id" class="block text-sm font-medium text-neutral-300 mb-2"&gt;Lapangan&lt;/label&gt;
                    &lt;select name="lapangan_id" id="lapangan_id" class="w-full bg-white/5 border border-white/20 text-white rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent"&gt;
                        &lt;option value=""&gt;Semua Lapangan&lt;/option&gt;
                        @foreach($lapangans as $lapangan)
                            &lt;option value="{{ $lapangan-&gt;id }}" {{ request('lapangan_id') == $lapangan-&gt;id ? 'selected' : '' }} class="text-neutral-900"&gt;{{ $lapangan-&gt;nama_lapangan }}&lt;/option&gt;
                        @endforeach
                    &lt;/select&gt;
                &lt;/div&gt;
                &lt;div&gt;
                    &lt;label for="status_booking" class="block text-sm font-medium text-neutral-300 mb-2"&gt;Status&lt;/label&gt;
                    &lt;select name="status_booking" id="status_booking" class="w-full bg-white/5 border border-white/20 text-white rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent"&gt;
                        &lt;option value=""&gt;Semua Status Valid&lt;/option&gt;
                        &lt;option value="dp_dibayar" {{ request('status_booking') == 'dp_dibayar' ? 'selected' : '' }} class="text-neutral-900"&gt;DP Dibayar&lt;/option&gt;
                        &lt;option value="lunas" {{ request('status_booking') == 'lunas' ? 'selected' : '' }} class="text-neutral-900"&gt;Lunas&lt;/option&gt;
                    &lt;/select&gt;
                &lt;/div&gt;
            &lt;/div&gt;

            &lt;div class="flex flex-wrap justify-end gap-3"&gt;
                &lt;a href="{{ route('admin.laporan.index') }}" class="px-4 py-2 border border-white/20 rounded-xl text-neutral-300 hover:bg-white/10 transition"&gt;Reset&lt;/a&gt;
                &lt;button type="submit" class="px-4 py-2 bg-amber-400 text-neutral-900 font-semibold rounded-xl hover:bg-amber-500 transition"&gt;Filter&lt;/button&gt;
                &lt;button type="button" onclick="window.print()" class="px-4 py-2 bg-white/20 text-white font-semibold rounded-xl hover:bg-white/30 transition flex items-center gap-2"&gt;
                    &lt;svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"&gt;
                        &lt;path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m1 3h8a2 2 0 002-2V9a2 2 0 00-2-2h-1V6a2 2 0 00-2-2H8a2 2 0 00-2 2v1H5a2 2 0 00-2 2v6a2 2 0 002 2h1v2a2 2 0 002 2z"/&gt;
                    &lt;/svg&gt;
                    Cetak / PDF
                &lt;/button&gt;
            &lt;/div&gt;
        &lt;/form&gt;
    &lt;/div&gt;

    &lt;!-- Ringkasan Per Lapangan --&gt;
    &lt;div id="print-area"&gt;
        &lt;div class="print-header"&gt;
            &lt;h1 style="font-size: 24px; font-weight: bold;"&gt;Laporan Penjualan Futsal&lt;/h1&gt;
            &lt;p&gt;Periode: {{ request('tanggal_mulai') ? \Carbon\Carbon::parse(request('tanggal_mulai'))-&gt;format('d M Y') : 'Awal' }} s/d {{ request('tanggal_selesai') ? \Carbon\Carbon::parse(request('tanggal_selesai'))-&gt;format('d M Y') : 'Sekarang' }}&lt;/p&gt;
            &lt;p&gt;Tanggal Cetak: {{ \Carbon\Carbon::now()-&gt;format('d M Y H:i') }}&lt;/p&gt;
            &lt;hr style="margin-top: 10px; margin-bottom: 20px; border: 1px solid #e5e7eb;"&gt;
        &lt;/div&gt;

        &lt;div class="mb-8"&gt;
            &lt;h3 class="text-lg font-semibold text-neutral-300 mb-4"&gt;Ringkasan Per Lapangan&lt;/h3&gt;
            &lt;div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl overflow-hidden"&gt;
                &lt;div class="overflow-x-auto"&gt;
                    &lt;table class="w-full text-sm"&gt;
                        &lt;thead&gt;
                            &lt;tr class="border-b border-white/20"&gt;
                                &lt;th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-400"&gt;Nama Lapangan&lt;/th&gt;
                                &lt;th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-neutral-400"&gt;Total Booking&lt;/th&gt;
                                &lt;th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-neutral-400"&gt;Total Pendapatan&lt;/th&gt;
                            &lt;/tr&gt;
                        &lt;/thead&gt;
                        &lt;tbody class="divide-y divide-white/10"&gt;
                            @forelse($ringkasanLapangan as $ringkasan)
                                &lt;tr class="hover:bg-white/5"&gt;
                                    &lt;td class="px-6 py-3.5 text-white"&gt;{{ $ringkasan['nama_lapangan'] }}&lt;/td&gt;
                                    &lt;td class="px-6 py-3.5 text-center text-neutral-300"&gt;{{ $ringkasan['total_booking'] }}&lt;/td&gt;
                                    &lt;td class="px-6 py-3.5 text-right text-neutral-300"&gt;Rp {{ number_format($ringkasan['total_pendapatan'], 0, ',', '.') }}&lt;/td&gt;
                                &lt;/tr&gt;
                            @empty
                                &lt;tr&gt;
                                    &lt;td colspan="3" class="px-6 py-10 text-center text-neutral-500"&gt;Tidak ada data&lt;/td&gt;
                                &lt;/tr&gt;
                            @endforelse
                        &lt;/tbody&gt;
                    &lt;/table&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;

        &lt;!-- Detail Transaksi --&gt;
        &lt;div class="mb-8"&gt;
            &lt;h3 class="text-lg font-semibold text-neutral-300 mb-4"&gt;Detail Transaksi&lt;/h3&gt;
            &lt;div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl overflow-hidden"&gt;
                &lt;div class="overflow-x-auto"&gt;
                    &lt;table class="w-full text-sm"&gt;
                        &lt;thead&gt;
                            &lt;tr class="border-b border-white/20"&gt;
                                &lt;th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-400"&gt;Penyewa&lt;/th&gt;
                                &lt;th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-400"&gt;Lapangan&lt;/th&gt;
                                &lt;th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-400"&gt;Tanggal&lt;/th&gt;
                                &lt;th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-400"&gt;Jam&lt;/th&gt;
                                &lt;th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-400"&gt;Status&lt;/th&gt;
                                &lt;th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-neutral-400"&gt;Total&lt;/th&gt;
                            &lt;/tr&gt;
                        &lt;/thead&gt;
                        &lt;tbody class="divide-y divide-white/10"&gt;
                            @forelse($bookings as $booking)
                                &lt;tr class="hover:bg-white/5"&gt;
                                    &lt;td class="px-6 py-3.5"&gt;
                                        &lt;div class="font-medium text-white"&gt;{{ $booking-&gt;user-&gt;name }}&lt;/div&gt;
                                    &lt;/td&gt;
                                    &lt;td class="px-6 py-3.5 text-neutral-300"&gt;{{ $booking-&gt;lapangan-&gt;nama_lapangan }}&lt;/td&gt;
                                    &lt;td class="px-6 py-3.5 text-neutral-400"&gt;{{ $booking-&gt;tanggal_main-&gt;format('d M Y') }}&lt;/td&gt;
                                    &lt;td class="px-6 py-3.5 text-neutral-400"&gt;{{ \Carbon\Carbon::parse($booking-&gt;jam_mulai)-&gt;format('H:i') }} - {{ \Carbon\Carbon::parse($booking-&gt;jam_selesai)-&gt;format('H:i') }}&lt;/td&gt;
                                    &lt;td class="px-6 py-3.5"&gt;
                                        @if($booking-&gt;status_booking == 'dp_dibayar')
                                            &lt;span class="px-2.5 py-1 rounded-full text-xs font-semibold border bg-amber-500/20 text-amber-300 border-amber-500/30"&gt;DP Dibayar&lt;/span&gt;
                                        @elseif($booking-&gt;status_booking == 'lunas')
                                            &lt;span class="px-2.5 py-1 rounded-full text-xs font-semibold border bg-green-500/20 text-green-300 border-green-500/30"&gt;Lunas&lt;/span&gt;
                                        @else
                                            &lt;span class="px-2.5 py-1 rounded-full text-xs font-semibold border bg-white/10 text-neutral-300 border-white/20"&gt;{{ ucfirst(str_replace('_', ' ', $booking-&gt;status_booking)) }}&lt;/span&gt;
                                        @endif
                                    &lt;/td&gt;
                                    &lt;td class="px-6 py-3.5 text-right font-medium text-white"&gt;Rp {{ number_format($booking-&gt;total_harga, 0, ',', '.') }}&lt;/td&gt;
                                &lt;/tr&gt;
                            @empty
                                &lt;tr&gt;
                                    &lt;td colspan="6" class="px-6 py-10 text-center text-neutral-500"&gt;Tidak ada transaksi pada periode ini&lt;/td&gt;
                                &lt;/tr&gt;
                            @endforelse
                        &lt;/tbody&gt;
                    &lt;/table&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

&lt;/x-admin-layout&gt;

