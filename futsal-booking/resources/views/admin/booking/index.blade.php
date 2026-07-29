<x-admin-layout>
  <div class="space-y-6 font-sans">

    <!-- Header Section & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-zinc-800/80 pb-6">
      <div>
        <div class="flex items-center gap-2 text-xs font-semibold text-amber-500 uppercase tracking-widest mb-1">
          <span>Admin Dashboard</span>
          <span class="text-zinc-600">/</span>
          <span class="text-zinc-400">Kelola Booking</span>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
          Daftar Booking
          <span class="text-xs font-normal px-2.5 py-0.5 rounded-full bg-zinc-800 text-zinc-400 border border-zinc-700/50">
            {{ $bookings->total() }} Data
          </span>
        </h1>
      </div>


    </div>

    <!-- Filter Section -->
    <div class="bg-[#141417] border border-zinc-800/80 rounded-xl p-4">
      <form method="GET" action="{{ route('admin.booking.index') }}" class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
        <div class="flex-1">
          <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-1.5">Filter Tanggal</label>
          <input type="date" name="tanggal" value="{{ request('tanggal') }}" 
                 class="w-full sm:w-auto bg-[#1A1A1E] border border-zinc-700/50 rounded-lg px-3 py-2 text-sm text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500/50 outline-none transition">
        </div>
        <div class="flex gap-2">
          <a href="{{ route('admin.booking.index') }}" 
             class="text-xs font-semibold text-zinc-300 bg-[#1A1A1E] border border-zinc-700/50 hover:border-rose-500/50 hover:bg-rose-500/10 hover:text-rose-400 px-4 py-2 rounded-lg transition inline-flex items-center gap-1.5">
            <span>↻</span> Reset Filter
          </a>
        </div>
      </form>
    </div>

    <!-- Table Container -->
    <div class="bg-[#141417] border border-zinc-800/80 rounded-xl overflow-hidden shadow-2xl">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          
          <!-- Table Header -->
          <thead class="bg-[#1A1A1E] text-[11px] font-semibold uppercase text-zinc-400 tracking-wider border-b border-zinc-800">
            <tr>
              <th class="py-4 px-6">User</th>
              <th class="py-4 px-6">Lapangan</th>
              <th class="py-4 px-6">Tanggal</th>
              <th class="py-4 px-6">Jam</th>
              <th class="py-4 px-6">Total Harga</th>
              <th class="py-4 px-6 text-center">Status</th>
              <th class="py-4 px-6 text-right">Aksi</th>
            </tr>
          </thead>

          <!-- Table Body -->
          <tbody class="divide-y divide-zinc-800/60 text-zinc-300">
            @forelse($bookings as $booking)
              <tr class="hover:bg-zinc-800/30 transition-colors">
                <td class="py-4 px-6 font-medium text-white">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-amber-500/10 text-amber-500 border border-amber-500/20 font-bold flex items-center justify-center text-xs">
                      {{ strtoupper(substr($booking->user->name ?? '?', 0, 1)) }}
                    </div>
                    <span class="capitalize">{{ $booking->user->name ?? '-' }}</span>
                  </div>
                </td>
                <td class="py-4 px-6 text-zinc-300 font-medium">{{ $booking->lapangan->nama_lapangan ?? '-' }}</td>
                <td class="py-4 px-6 text-zinc-400 font-mono text-xs">{{ \Carbon\Carbon::parse($booking->tanggal_main)->format('d/m/Y') }}</td>
                <td class="py-4 px-6 text-zinc-400 font-mono text-xs">{{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}</td>
                <td class="py-4 px-6 font-semibold text-white">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                <td class="py-4 px-6 text-center">
                  @php
                      $statusColors = [
                          'lunas' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                          'dp_dibayar' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                          'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                          'batal' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                          'menunggu_keputusan_customer' => 'bg-amber-500/10 text-amber-300 border-amber-500/30',
                          'menunggu_refund' => 'bg-purple-500/10 text-purple-300 border-purple-500/30',
                          'direfund' => 'bg-sky-500/10 text-sky-300 border-sky-500/30',
                      ];
                      $statusDotColors = [
                          'lunas' => 'bg-emerald-400',
                          'dp_dibayar' => 'bg-amber-400 animate-pulse',
                          'pending' => 'bg-amber-400 animate-pulse',
                          'batal' => 'bg-rose-400',
                          'menunggu_keputusan_customer' => 'bg-amber-400 animate-bounce',
                          'menunggu_refund' => 'bg-purple-400 animate-pulse',
                          'direfund' => 'bg-sky-400',
                      ];
                      
                      $colorClass = $statusColors[$booking->status_booking] ?? 'bg-zinc-500/10 text-zinc-400 border-zinc-500/20';
                      $dotClass = $statusDotColors[$booking->status_booking] ?? 'bg-zinc-400';
                  @endphp
                  <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium border {{ $colorClass }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                    @php
                        $statusLabels = [
                            'pending' => 'Menunggu Pembayaran',
                            'dp_dibayar' => 'DP Dibayar',
                            'lunas' => 'Lunas',
                            'expired' => 'Kedaluwarsa',
                            'batal' => 'Dibatalkan',
                            'menunggu_keputusan_customer' => 'Menunggu Keputusan Customer',
                            'menunggu_refund' => 'Menunggu Refund',
                            'direfund' => 'Direfund',
                        ];
                    @endphp
                    {{ $statusLabels[$booking->status_booking] ?? ucfirst(str_replace('_', ' ', $booking->status_booking)) }}
                  </span>
                  @if($booking->status_booking === 'direfund' && $booking->nominal_refund)
                    <div class="text-[10px] text-sky-400 mt-1 font-medium">
                      Rp {{ number_format($booking->nominal_refund, 0, ',', '.') }}
                    </div>
                  @endif
                </td>
                <td class="py-4 px-6 text-right flex items-center justify-end gap-2">
                  @if(in_array($booking->status_booking, ['menunggu_refund', 'menunggu_keputusan_customer']))
                    <form method="POST" action="{{ route('admin.booking.confirm-refund', $booking) }}" data-confirm-message="Tandai refund dana sebagai selesai? Status booking akan diubah menjadi Batal.">
                      @csrf
                      <button type="submit" class="text-xs font-bold text-emerald-400 hover:text-white bg-emerald-500/10 hover:bg-emerald-500/30 px-3 py-1.5 rounded-lg border border-emerald-500/30 transition inline-block">
                        Tandai Refund Selesai
                      </button>
                    </form>
                  @endif
                  <a href="{{ route('admin.booking.show', $booking) }}" class="text-xs font-medium text-zinc-400 hover:text-white bg-zinc-800/80 hover:bg-zinc-700/80 px-3 py-1.5 rounded-lg border border-zinc-700/50 transition inline-block">
                    Lihat
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="py-8 text-center text-zinc-500">
                  Tidak ada data booking.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Table Pagination / Summary Footer -->
      <div class="px-6 py-3.5 bg-[#1A1A1E] border-t border-zinc-800 flex items-center justify-between text-xs text-zinc-400">
        @if(method_exists($bookings, 'links'))
            <span>Menampilkan {{ $bookings->firstItem() ?? 0 }}-{{ $bookings->lastItem() ?? 0 }} dari {{ $bookings->total() }} booking</span>
            <div class="flex gap-2">
                {{ $bookings->links() }}
            </div>
        @else
            <span>Total {{ $bookings->count() }} booking</span>
        @endif
      </div>
    </div>

  </div>
</x-admin-layout>
