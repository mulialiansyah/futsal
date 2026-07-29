<x-app-layout>
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('customer.booking.index') }}" class="text-white/70 hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h2 class="text-2xl sm:text-3xl font-bold text-white">Detail Booking</h2>
    </div>

    <div class="max-w-3xl mx-auto space-y-5">
        <div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl p-6">
            <div class="flex items-center gap-4 mb-6 rounded-xl border border-white/10 bg-white/5 p-3">
                <img src="{{ $booking->lapangan->fotoUtama->url }}"
                     alt="{{ $booking->lapangan->nama_lapangan }}"
                     class="h-20 w-28 rounded-lg object-cover">
                <div>
                    <p class="text-xs text-neutral-400">Lapangan yang kamu pesan</p>
                    <p class="font-bold text-white">{{ $booking->lapangan->nama_lapangan }}</p>
                    <p class="text-xs text-neutral-400 capitalize">{{ $booking->lapangan->jenis_lapangan }} · {{ $booking->lapangan->tipe_venue }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                    <p class="text-xs text-neutral-400 mb-1">Lapangan</p>
                    <p class="font-bold text-white">{{ $booking->lapangan->nama_lapangan }}</p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                    <p class="text-xs text-neutral-400 mb-1">Tanggal Main</p>
                    <p class="font-bold text-white">{{ \Carbon\Carbon::parse($booking->tanggal_main)->isoFormat('dddd, D MMMM YYYY') }}</p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                    <p class="text-xs text-neutral-400 mb-1">Jam Main</p>
                    <p class="font-bold text-white">{{ substr($booking->jam_mulai, 0, 5) }} – {{ substr($booking->jam_selesai, 0, 5) }}</p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                    <p class="text-xs text-neutral-400 mb-1">Status</p>
                    @php
                        $colors = [
                            'pending' => 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30',
                            'dp_dibayar' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                            'lunas' => 'bg-green-500/20 text-green-300 border-green-500/30',
                            'expired' => 'bg-red-500/20 text-red-300 border-red-500/30',
                            'batal' => 'bg-neutral-500/20 text-neutral-300 border-neutral-500/30',
                            'menunggu_keputusan_customer' => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
                            'menunggu_refund' => 'bg-purple-500/20 text-purple-300 border-purple-500/30',
                            'direfund' => 'bg-sky-500/20 text-sky-300 border-sky-500/30',
                        ];
                        $statusLabelsCustomer = [
                            'pending' => 'Menunggu Pembayaran',
                            'dp_dibayar' => 'DP Dibayar',
                            'lunas' => 'Lunas',
                            'expired' => 'Kedaluwarsa',
                            'batal' => 'Dibatalkan',
                            'menunggu_keputusan_customer' => 'Menunggu Keputusan Anda',
                            'menunggu_refund' => 'Menunggu Refund',
                            'direfund' => 'Sudah Direfund',
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-bold border {{ $colors[$booking->status_booking] ?? 'bg-neutral-500/20' }}">
                        {{ $statusLabelsCustomer[$booking->status_booking] ?? ucfirst(str_replace('_', ' ', $booking->status_booking)) }}
                    </span>
                </div>
            </div>

            <div class="border-t border-white/10 pt-4 mb-6">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-neutral-400">Total Harga:</span>
                    <span class="font-bold text-white">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-neutral-400">Total Dibayar:</span>
                    <span class="font-bold text-green-300">Rp {{ number_format($booking->total_dibayar, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-lg border-t border-white/10 pt-2 mt-2">
                    <span class="font-bold text-neutral-300">Sisa Tagihan:</span>
                    <span class="font-extrabold text-red-300">Rp {{ number_format($booking->sisa_tagihan, 0, ',', '.') }}</span>
                </div>
            </div>

            @if($booking->status_booking === 'pending' && $booking->payment_deadline)
                <div class="bg-yellow-500/20 border border-yellow-500/30 rounded-xl p-6 mb-6">
                    <p class="text-sm text-yellow-300 mb-2">⏰ Sisa Waktu Pembayaran:</p>
                    <div class="text-5xl font-mono font-bold text-yellow-300 text-center mb-2" id="countdownDisplay">
                        {{ $booking->sisa_waktu_format }}
                    </div>
                    <p class="text-xs text-neutral-400 text-center">Deadline: {{ $booking->payment_deadline->format('d M Y H:i') }}</p>
                </div>
            @endif

            @if($booking->status_booking === 'pending' && $booking->metode_pembayaran === 'cash')
                <div class="mb-6 rounded-xl border border-amber-500/30 bg-amber-500/20 p-4">
                    <p class="text-sm font-bold text-amber-300">💵 Pembayaran cash di lokasi</p>
                    <p class="mt-1 text-xs text-neutral-300">Silakan bayar Rp {{ number_format($booking->sisa_tagihan, 0, ',', '.') }} langsung di tempat saat datang. Admin akan mengonfirmasi pembayaran Anda.</p>
                </div>
            @endif

            @if($booking->status_booking === 'dp_dibayar' && $booking->pelunasan_deadline)
                <div class="bg-blue-500/20 border border-blue-500/30 rounded-xl p-6 mb-6">
                    <p class="text-sm text-blue-300 font-semibold mb-2">⏰ Waktu Sisa untuk Pelunasan:</p>
                    <div class="text-5xl font-mono font-bold text-blue-300 text-center mb-2" id="countdownPelunasanDisplay">
                        {{ $booking->sisa_waktu_pelunasan_format }}
                    </div>
                    <p class="text-xs text-neutral-400 text-center">Deadline: {{ $booking->pelunasan_deadline->format('d M Y H:i') }}</p>
                </div>
            @endif

            @if($booking->status_booking === 'menunggu_keputusan_customer')
                <div class="bg-amber-500/20 border border-amber-500/40 rounded-2xl p-6 mb-6">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-2xl">⚠️</span>
                        <div>
                            <h4 class="font-bold text-amber-300 text-base">Lapangan Ditutup Sementara oleh Pengelola</h4>
                            <p class="text-xs text-neutral-300 mt-0.5">Alasan: <strong class="text-white">{{ $booking->alasan_penutupan ?? 'Pemeliharaan fasilitas.' }}</strong></p>
                        </div>
                    </div>

                    @if($booking->opsi_deadline)
                        <div class="my-4 bg-amber-950/60 border border-amber-500/30 rounded-xl p-4 text-center">
                            <p class="text-xs text-amber-300 uppercase tracking-wider font-semibold mb-1">⏳ Sisa Waktu Memilih Opsi (Max 3x24 Jam):</p>
                            <div class="text-3xl font-mono font-extrabold text-amber-300" id="countdownOpsiDisplay">
                                {{ $booking->sisa_waktu_opsi_format }}
                            </div>
                            <p class="text-[11px] text-neutral-400 mt-1">Jika sisa waktu habis, status otomatis diubah ke Pengembalian Dana (Refund).</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 gap-5 mt-4">
                        <form method="POST" action="{{ route('customer.booking.choose-refund', $booking) }}" class="bg-white/5 border border-white/10 rounded-xl p-4 space-y-3.5">
                            @csrf
                            <div>
                                <h5 class="text-sm font-bold text-amber-200 mb-1 flex items-center gap-1.5"><span>💵</span> Opsi A: Ajukan Refund Dana</h5>
                                <p class="text-[11px] text-neutral-400 mb-2">Admin akan mengembalikan uang yang sudah dibayarkan secara manual (transfer ke rekening/e-wallet Anda). Silakan isi tujuan transfer di bawah.</p>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-amber-300/80 mb-1.5">
                                    Tujuan Transfer Refund <span class="text-rose-400">*</span>
                                </label>
                                <p class="text-[11px] text-neutral-500 mb-1.5">Isi dengan format: <span class="font-semibold text-neutral-300">[Nama Bank / E-Wallet] + [Nomor Rekening / No. HP] + [Atas Nama]</span></p>
                                <input type="text" name="refund_tujuan" required minlength="8" maxlength="255"
                                       value="{{ old('refund_tujuan') }}"
                                       placeholder="Contoh: BCA 731098xxxx a.n. Ahmad Fauzi — atau OVO 08123456xxxx a.n. Ahmad Fauzi"
                                       class="w-full bg-neutral-950 border border-white/10 rounded-lg px-3.5 py-2.5 text-sm text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500/50 outline-none transition placeholder:text-neutral-600">
                                @error('refund_tujuan')
                                    <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit"
                                    data-confirm-message="Yakin ingin mengajukan Refund Dana? Pastikan tujuan transfer di atas sudah benar. Admin akan memproses pengembalian uang Anda secara manual."
                                    class="w-full bg-amber-600 hover:bg-amber-500 text-white font-bold py-3 px-4 rounded-xl text-sm transition shadow-lg flex items-center justify-center gap-2">
                                <span>💵</span> Ajukan Pengembalian Dana
                            </button>
                        </form>

                        <a href="{{ route('customer.booking.reschedule-form', $booking) }}" class="bg-white/5 border border-emerald-500/20 hover:border-emerald-500/40 hover:bg-emerald-500/5 rounded-xl p-4 transition w-full text-left">
                            <h5 class="text-sm font-bold text-emerald-300 mb-1 flex items-center gap-1.5"><span>🔄</span> Opsi B: Pindah Lapangan / Jadwal</h5>
                            <p class="text-[11px] text-neutral-400 mb-0">Ubah jadwal bermain atau pindah ke lapangan lain tanpa perlu refund.</p>
                        </a>
                    </div>
                </div>
            @endif

            @if($booking->status_booking === 'menunggu_refund')
                <div class="bg-purple-500/20 border border-purple-500/40 rounded-2xl p-5 mb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-2xl">⏳</span>
                        <div>
                            <h4 class="font-bold text-purple-300 text-sm">Permintaan Refund Dana Sedang Diproses</h4>
                            <p class="text-xs text-neutral-300 mt-1">Permintaan pengembalian dana Anda telah tercatat. Admin akan segera mentransfer kembali uang yang sudah dibayarkan ke tujuan di bawah ini.</p>
                        </div>
                    </div>
                    @if($booking->refund_tujuan)
                        <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                            <p class="text-[11px] text-purple-300/80 uppercase tracking-wider font-bold mb-1">Tujuan Transfer (Yang Anda Kirim)</p>
                            <p class="text-sm font-bold text-white">{{ $booking->refund_tujuan }}</p>
                        </div>
                    @endif
                </div>
            @endif

            @if($booking->status_booking === 'direfund')
                <div class="bg-sky-500/15 border border-sky-500/40 rounded-2xl p-5 mb-6 shadow-lg shadow-sky-900/20 backdrop-blur-xl">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-full bg-sky-500/20 flex items-center justify-center text-2xl">💸</div>
                        <div>
                            <h4 class="font-bold text-sky-200 text-base">Refund Sudah Dikirimkan oleh Admin</h4>
                            <p class="text-xs text-neutral-300 mt-0.5">Admin sudah mentransfer kembali dana sesuai nominal di bawah ini. Silakan cek rekening / e-wallet Anda.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                        <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                            <p class="text-[11px] text-sky-400/80 uppercase tracking-wide font-bold mb-1">Nominal Refund</p>
                            <p class="text-2xl font-black text-white">Rp {{ number_format($booking->nominal_refund ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                            <p class="text-[11px] text-sky-400/80 uppercase tracking-wide font-bold mb-1">Tanggal Refund</p>
                            <p class="text-sm font-bold text-white">{{ $booking->tanggal_refund?->isoFormat('D MMMM YYYY, HH:mm') ?? '-' }}</p>
                        </div>
                    </div>
                    @if($booking->refund_tujuan)
                        <div class="bg-white/5 rounded-xl p-4 border border-white/10 mb-4">
                            <p class="text-[11px] text-sky-400/80 uppercase tracking-wide font-bold mb-1.5">Ditransfer Ke</p>
                            <p class="text-sm font-bold text-white">{{ $booking->refund_tujuan }}</p>
                        </div>
                    @endif
                    @if($booking->catatan_refund)
                        <div class="bg-white/5 rounded-xl p-4 border border-white/10 mb-4">
                            <p class="text-[11px] text-sky-400/80 uppercase tracking-wide font-bold mb-1.5">Catatan Admin</p>
                            <p class="text-sm text-neutral-200 whitespace-pre-wrap">{{ $booking->catatan_refund }}</p>
                        </div>
                    @endif
                    @if($booking->bukti_refund_url)
                        <a href="{{ $booking->bukti_refund_url }}" target="_blank"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-sky-500 hover:bg-sky-600 active:scale-[0.98] text-white font-bold px-6 py-3 rounded-xl transition shadow-lg shadow-sky-500/25">
                            <span>📄</span>
                            <span>Unduh Bukti Transfer Refund</span>
                        </a>
                    @endif
                </div>
            @endif

            @if(in_array($booking->status_booking, ['expired', 'batal']))
                <div class="bg-red-500/20 border border-red-500/30 rounded-xl p-4 mb-6">
                    <p class="text-sm text-red-300 font-bold">
                        ❌ Booking ini sudah dibatalkan/expired. Slot lapangan telah di-release.
                    </p>
                </div>
            @endif

            @if(in_array($booking->status_booking, ['dp_dibayar', 'lunas']))
                <form method="POST" action="{{ route('customer.booking.request-cancel-refund', $booking) }}" class="bg-rose-500/10 border border-rose-500/30 rounded-2xl p-5 mb-6 space-y-4">
                    @csrf
                    <div class="flex items-start gap-3">
                        <div class="w-11 h-11 rounded-full bg-rose-500/20 flex items-center justify-center text-xl shrink-0">🚫</div>
                        <div class="flex-1">
                            <h4 class="font-bold text-rose-200 text-base mb-1">Batalkan Booking & Ajukan Pengembalian Dana</h4>
                            <p class="text-xs text-neutral-300 mt-0.5">Jika Anda ingin membatalkan dan meminta pengembalian dana (refund) sebesar <span class="font-bold text-emerald-300">Rp {{ number_format($booking->total_dibayar, 0, ',', '.') }}</span>, isi tujuan transfer di bawah lalu submit. Admin akan memproses transfer secara manual maksimal 1x24 jam kerja.</p>
                        </div>
                    </div>
                    <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-rose-300/80 mb-1.5">
                            Tujuan Transfer Refund <span class="text-rose-400">*</span>
                        </label>
                        <p class="text-[11px] text-neutral-500 mb-1.5">Isi dengan format: <span class="font-semibold text-neutral-300">[Nama Bank / E-Wallet] + [Nomor Rekening / No. HP] + [Atas Nama]</span></p>
                        <input type="text" name="refund_tujuan" required minlength="8" maxlength="255"
                               value="{{ old('refund_tujuan') }}"
                               placeholder="Contoh: BRI 0341xxxxxx a.n. Siti Rahmawati — atau GoPay 0899xxxxxx a.n. Siti Rahmawati"
                               class="w-full bg-neutral-950 border border-white/10 rounded-lg px-3.5 py-2.5 text-sm text-white focus:border-rose-500 focus:ring-1 focus:ring-rose-500/50 outline-none transition placeholder:text-neutral-600">
                        @error('refund_tujuan')
                            <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                            data-confirm-message="Yakin ingin MEMBATALKAN booking ini dan mengajukan pengembalian dana? Slot lapangan akan dilepas dan tidak bisa dikembalikan."
                            class="w-full bg-rose-600 hover:bg-rose-500 active:scale-[0.98] text-white font-bold px-6 py-3 rounded-xl transition shadow-lg shadow-rose-900/20 inline-flex items-center justify-center gap-2">
                        <span>🚫</span> Batalkan Booking & Ajukan Refund
                    </button>
                </form>
            @endif

            <div class="flex flex-col sm:flex-row gap-3">
                @if($booking->status_booking === 'pending')
                    <form method="POST" action="{{ route('customer.booking.destroy', $booking) }}"
                          data-confirm-message="Yakin batalkan booking ini?" class="flex-1">
                        @csrf @method('DELETE')
                        <button class="w-full bg-red-500 hover:bg-red-600 text-white font-bold px-6 py-3 rounded-xl transition">
                            Batalkan Booking
                        </button>
                    </form>
                @endif

                @if(in_array($booking->status_booking, ['dp_dibayar', 'lunas', 'menunggu_keputusan_customer', 'menunggu_refund', 'direfund', 'refund_selesai']))
                    <a href="{{ route('customer.booking.download-dp', $booking) }}" 
                       class="flex-1 text-center bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-6 py-3 rounded-xl transition shadow-lg flex items-center justify-center gap-2">
                        📄 {{ $booking->sisa_tagihan == 0 ? 'Unduh Bukti Lunas' : 'Unduh Bukti DP' }}
                    </a>
                @endif
            </div>
        </div>

        {{-- Section Pembayaran --}}
        <div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-white">Riwayat Pembayaran</h3>
                
                @php
                    $hasPending = $booking->pembayarans->where('status_verifikasi', 'pending')->isNotEmpty();
                    $hasRejected = $booking->pembayarans->where('status_verifikasi', 'ditolak')->isNotEmpty();
                    $hasMidtransPending = $booking->pembayarans->where('status_verifikasi', 'pending')->where('metode_pembayaran', 'midtrans')->isNotEmpty();
                @endphp

                @if($booking->metode_pembayaran === 'cash' && $booking->status_booking === 'pending')
                    <span class="rounded-xl border border-amber-500/30 bg-amber-500/20 px-4 py-2 text-sm font-bold text-amber-300">
                        💵 Bayar di Tempat
                    </span>
                @elseif(in_array($booking->status_booking, ['pending', 'dp_dibayar']) && $hasMidtransPending)
                    <a href="{{ route('customer.pembayaran.create', $booking) }}"
                       class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-bold transition">
                        Lanjutkan Midtrans
                    </a>
                @elseif($booking->metode_pembayaran !== 'cash' && in_array($booking->status_booking, ['pending', 'dp_dibayar']) && !$hasPending)
                    <a href="{{ route('customer.pembayaran.create', $booking) }}"
                       class="bg-amber-400 hover:bg-amber-500 text-neutral-900 px-4 py-2 rounded-xl text-sm font-bold transition">
                        💳 {{ $booking->status_booking === 'pending' ? 'Bayar DP / Lunas' : 'Bayar Pelunasan' }}
                    </a>
                @endif
            </div>

            @if($booking->metode_pembayaran !== 'cash')
                @if($booking->pembayarans->isEmpty())
                    <div class="bg-yellow-500/20 border border-yellow-500/30 rounded-xl p-4 mb-4 text-center">
                        <p class="text-sm text-yellow-300 font-semibold">
                            ⚠️ Belum ada bukti pembayaran.
                        </p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($booking->pembayarans as $idx => $payment)
                            <div class="border rounded-xl p-4 {{ $payment->status_verifikasi === 'diterima' ? 'border-green-500/30 bg-green-500/10' : ($payment->status_verifikasi === 'ditolak' ? 'border-red-500/30 bg-red-500/10' : 'border-white/10') }}">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="text-xs text-neutral-400">Pembayaran #{{ $idx + 1 }} - {{ $payment->created_at->format('d M Y H:i') }}</p>
                                        <p class="font-bold text-white text-lg">Rp {{ number_format($payment->nominal, 0, ',', '.') }}</p>
                                        <p class="text-xs text-neutral-400 mt-1">{{ $payment->metode_pembayaran === 'midtrans' ? 'Midtrans' : 'Metode lama' }}</p>
                                    </div>
                                    @php
                                        $verifikasi = [
                                            'pending'  => 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30',
                                            'diterima' => 'bg-green-500/20 text-green-300 border-green-500/30',
                                            'ditolak'  => 'bg-red-500/20 text-red-300 border-red-500/30',
                                        ];
                                        $label = [
                                            'pending'  => '⏳ Menunggu Verifikasi',
                                            'diterima' => '✅ Diterima',
                                            'ditolak'  => '❌ Ditolak',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold border {{ $verifikasi[$payment->status_verifikasi] ?? 'bg-neutral-500/20' }}">
                                        {{ $label[$payment->status_verifikasi] ?? $payment->status_verifikasi }}
                                    </span>
                                </div>

                                @if($payment->bukti_transfer)
                                    <div class="mt-3">
                                        <p class="text-xs text-neutral-400 mb-2">Screenshot Pembayaran:</p>
                                        <img src="{{ asset('storage/' . $payment->bukti_transfer) }}"
                                             class="max-w-xs rounded-xl border border-white/10 object-contain max-h-32">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>

    <script>
        let countdownInterval;

        function updateCountdown() {
            @if($booking->status_booking === 'lunas')
                // Stop timer if already paid in full
                if (countdownInterval) {
                    clearInterval(countdownInterval);
                }
                return;
            @endif

            @if($booking->status_booking === 'pending' && $booking->payment_deadline)
                const deadline = new Date({{ $booking->payment_deadline->timestamp * 1000 }});
                const now = new Date();
                const sisa = deadline - now;

                if (sisa <= 0) {
                    document.getElementById('countdownDisplay').textContent = '00:00:00';
                } else {
                    const jam = Math.floor(sisa / (1000 * 60 * 60));
                    const menit = Math.floor((sisa % (1000 * 60 * 60)) / (1000 * 60));
                    const detik = Math.floor((sisa % (1000 * 60)) / 1000);
                    document.getElementById('countdownDisplay').textContent =
                        `${String(jam).padStart(2, '0')}:${String(menit).padStart(2, '0')}:${String(detik).padStart(2, '0')}`;
                }
            @endif

            @if($booking->status_booking === 'dp_dibayar' && $booking->pelunasan_deadline)
                const deadline2 = new Date({{ $booking->pelunasan_deadline->timestamp * 1000 }});
                const now2 = new Date();
                const sisa2 = deadline2 - now2;

                if (sisa2 <= 0) {
                    document.getElementById('countdownPelunasanDisplay').textContent = '00:00:00';
                } else {
                    const jam2 = Math.floor(sisa2 / (1000 * 60 * 60));
                    const menit2 = Math.floor((sisa2 % (1000 * 60 * 60)) / (1000 * 60));
                    const detik2 = Math.floor((sisa2 % (1000 * 60)) / 1000);
                    document.getElementById('countdownPelunasanDisplay').textContent =
                        `${String(jam2).padStart(2, '0')}:${String(menit2).padStart(2, '0')}:${String(detik2).padStart(2, '0')}`;
                }
            @endif

            @if($booking->status_booking === 'menunggu_keputusan_customer' && $booking->opsi_deadline)
                const deadlineOpsi = new Date({{ $booking->opsi_deadline->timestamp * 1000 }});
                const nowOpsi = new Date();
                const sisaOpsi = deadlineOpsi - nowOpsi;
                const elOpsi = document.getElementById('countdownOpsiDisplay');

                if (elOpsi) {
                    if (sisaOpsi <= 0) {
                        elOpsi.textContent = '00:00:00';
                    } else {
                        const hOpsi = Math.floor(sisaOpsi / (1000 * 60 * 60));
                        const mOpsi = Math.floor((sisaOpsi % (1000 * 60 * 60)) / (1000 * 60));
                        const sOpsi = Math.floor((sisaOpsi % (1000 * 60)) / 1000);
                        elOpsi.textContent =
                            `${String(hOpsi).padStart(2, '0')}:${String(mOpsi).padStart(2, '0')}:${String(sOpsi).padStart(2, '0')}`;
                    }
                }
            @endif
        }

        @if($booking->status_booking !== 'lunas')
            updateCountdown();
            countdownInterval = setInterval(updateCountdown, 1000);
        @endif
    </script>
</x-app-layout>
