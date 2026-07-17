<x-admin-layout>

    <!-- Hero / CTA -->
    <div class="bg-neutral-900 rounded-2xl px-6 py-6 mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-white font-semibold text-lg sm:text-xl">Daftarkan lapangan kamu dan dapatkan penghasilan</p>
            <p class="text-neutral-400 text-sm mt-1">Tambah lapangan baru dalam hitungan menit</p>
        </div>
        <a href="{{ route('admin.lapangan.create') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-yellow-400 text-neutral-900 font-semibold text-sm hover:bg-yellow-300 transition shrink-0">
            Daftarkan Lapangan
        </a>
    </div>

    <!-- Ringkasan Pendapatan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <div class="bg-white border border-neutral-200 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm text-neutral-500">Pendapatan bulan ini</p>
                <div class="w-9 h-9 rounded-full bg-emerald-50 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 10v2"/>
                    </svg>
                </div>
            </div>
            <p class="font-display text-2xl sm:text-3xl text-neutral-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white border border-neutral-200 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm text-neutral-500">Transaksi berhasil</p>
                <div class="w-9 h-9 rounded-full bg-sky-50 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="font-display text-2xl sm:text-3xl text-neutral-900">{{ $totalBookings }}</p>
        </div>
    </div>

    <!-- Riwayat Penyewaan -->
    <div class="bg-white border border-neutral-200 rounded-2xl p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-neutral-900">Riwayat Penyewaan</h2>
            <a href="{{ route('admin.booking.index') }}" class="text-sm text-neutral-500 hover:text-neutral-900 flex items-center gap-1">
                Lihat Semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        @if($recentBookings->count() > 0)
            <div class="divide-y divide-neutral-100">
                @foreach($recentBookings as $booking)
                    <div class="flex items-center gap-4 py-3 first:pt-0 last:pb-0">
                        <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-sm text-neutral-900 truncate">{{ $booking->user->name }}</p>
                            <p class="text-xs text-neutral-500">{{ $booking->tanggal_main->format('d F Y') }}, {{ $booking->jam_mulai }}</p>
                        </div>
                        <span class="text-xs font-medium bg-neutral-100 text-neutral-600 px-3 py-1 rounded-full shrink-0">
                            {{ $booking->lapangan->nama_lapangan }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-neutral-500 text-center py-6">Belum ada riwayat penyewaan.</p>
        @endif
    </div>

    <!-- Data Lainnya -->
    <div>
        <h2 class="font-semibold text-neutral-900 mb-4">Data Lainnya</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white border border-neutral-200 rounded-2xl p-5">
                <p class="text-sm text-neutral-500 mb-1">Total Lapangan</p>
                <p class="text-2xl font-bold text-neutral-900">{{ $totalLapangan }}</p>
            </div>
            <div class="bg-white border border-neutral-200 rounded-2xl p-5">
                <p class="text-sm text-neutral-500 mb-1">Pembayaran Pending</p>
                <p class="text-2xl font-bold text-neutral-900">{{ $pendingPayments }}</p>
            </div>
        </div>
    </div>

</x-admin-layout>