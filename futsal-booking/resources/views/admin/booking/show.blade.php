<x-admin-layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="font-display text-3xl text-white">Detail Booking</h1>
        </div>
        <a href="{{ route('admin.booking.index') }}" class="bg-neutral-800 hover:bg-neutral-700 text-white px-4 py-2 rounded-lg border border-white/10 transition">Kembali</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="rounded-xl bg-neutral-900 border border-white/10 overflow-hidden p-6">
            <h2 class="font-display text-lg text-white tracking-wide mb-4">Informasi Booking</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-neutral-400 mb-1">User</label>
                    <p class="text-white">{{ $booking->user->name }} ({{ $booking->user->email }})</p>
                </div>

                <div>
                    <label class="block text-sm text-neutral-400 mb-1">Lapangan</label>
                    <p class="text-white">{{ $booking->lapangan->nama_lapangan }}</p>
                </div>

                <div>
                    <label class="block text-sm text-neutral-400 mb-1">Tanggal Main</label>
                    <p class="text-white">{{ $booking->tanggal_main->format('d/m/Y') }}</p>
                </div>

                <div>
                    <label class="block text-sm text-neutral-400 mb-1">Jam</label>
                    <p class="text-white">{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</p>
                </div>

                <div>
                    <label class="block text-sm text-neutral-400 mb-1">Total Harga</label>
                    <p class="text-white text-xl font-bold">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                </div>

                <div>
                    <label class="block text-sm text-neutral-400 mb-1">Status Booking</label>
                    <p class="text-white">
                        <span @class([
                            'px-2.5 py-1 rounded-full text-xs font-semibold border',
                            'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' => $booking->status_booking === 'lunas',
                            'bg-blue-500/10 text-blue-400 border-blue-500/20' => $booking->status_booking === 'dp_dibayar',
                            'bg-amber-500/10 text-amber-400 border-amber-500/20' => $booking->status_booking === 'pending' || $booking->status_booking === 'menunggu_keputusan_customer' || $booking->status_booking === 'menunggu_refund',
                            'bg-red-500/10 text-red-400 border-red-500/20' => $booking->status_booking === 'batal' || $booking->status_booking === 'expired',
                            'bg-sky-500/10 text-sky-300 border-sky-500/30' => $booking->status_booking === 'direfund',
                            'bg-neutral-500/10 text-neutral-400 border-neutral-500/20' => !in_array($booking->status_booking, ['lunas', 'pending', 'batal', 'dp_dibayar', 'expired', 'direfund', 'menunggu_keputusan_customer', 'menunggu_refund']),
                        ])>
                            @php
                                $statusLabelMap = [
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
                            {{ $statusLabelMap[$booking->status_booking] ?? ucfirst(str_replace('_', ' ', $booking->status_booking)) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="rounded-xl bg-neutral-900 border border-white/10 overflow-hidden p-6">
            <h2 class="font-display text-lg text-white tracking-wide mb-4">Ringkasan Pembayaran</h2>
            
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-neutral-400">Total Harga</span>
                    <span class="font-semibold text-white">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-neutral-400">Total Dibayar</span>
                    <span class="font-semibold text-emerald-400">Rp {{ number_format($booking->total_dibayar, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between border-t border-white/10 pt-2 mt-2">
                    <span class="font-semibold text-neutral-300">Sisa Tagihan</span>
                    <span class="font-bold text-xl {{ $booking->sisa_tagihan > 0 ? 'text-red-400' : 'text-emerald-400' }}">
                        Rp {{ number_format($booking->sisa_tagihan, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tombol Konfirmasi Pembayaran Cash --}}
    @if(in_array($booking->status_booking, ['pending', 'dp_dibayar']) && $booking->sisa_tagihan > 0 && ($booking->metode_pembayaran === 'cash' || $booking->status_booking === 'dp_dibayar'))
        <div class="rounded-xl bg-amber-500/10 border border-amber-500/20 p-6 mb-6">
            <h3 class="font-bold text-amber-400 mb-2">💵 Konfirmasi Pembayaran Cash</h3>
            <p class="text-sm text-amber-300 mb-3">
                Customer telah membayar langsung di tempat (cash)? Klik tombol di bawah untuk mengonfirmasi pembayaran.
            </p>
            <form action="{{ route('admin.pembayaran.confirm-cash', $booking) }}" method="POST"
                  data-confirm-message="Yakin konfirmasi pembayaran cash Rp {{ number_format($booking->sisa_tagihan, 0, ',', '.') }}?">
                @csrf
                <input type="hidden" name="nominal" value="{{ $booking->sisa_tagihan }}">
                <button type="submit"
                        class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-6 py-3 rounded-lg transition w-full">
                    💵 Konfirmasi Pembayaran Cash — Rp {{ number_format($booking->sisa_tagihan, 0, ',', '.') }}
                </button>
            </form>
        </div>
    @endif

    {{-- Tombol: Admin Batalkan Booking (untuk status aktif yang bisa dibatalkan admin) --}}
    @if(in_array($booking->status_booking, ['pending', 'dp_dibayar', 'lunas']))
        <div class="rounded-xl bg-red-500/10 border border-red-500/20 p-6 mb-6">
            <h3 class="font-bold text-red-400 mb-1">❌ Batalkan Booking</h3>
            <p class="text-sm text-red-300/80 mb-4">
                Batalkan booking ini secara manual. 
                @if($booking->total_dibayar > 0)
                    Customer sudah melakukan pembayaran sebesar <span class="font-bold text-white">Rp {{ number_format($booking->total_dibayar, 0, ',', '.') }}</span> — setelah dibatalkan, gunakan panel Proses Refund di bawah untuk mengembalikan dana.
                @else
                    Belum ada pembayaran yang masuk, slot lapangan akan langsung dilepas.
                @endif
            </p>
            <form action="{{ route('admin.booking.cancel', $booking) }}" method="POST"
                  data-confirm-message="Yakin ingin membatalkan booking #{{ $booking->id }} milik {{ $booking->user->name }}? Tindakan ini tidak dapat dibatalkan.">
                @csrf
                @method('PATCH')
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 active:scale-[0.98] text-white font-bold px-6 py-3 rounded-lg transition">
                    ❌ Batalkan Booking Ini
                </button>
            </form>
        </div>
    @endif

    {{-- Tombol: Konfirmasi Terima Pengajuan Refund dari Customer --}}
    @if(in_array($booking->status_booking, ['menunggu_refund', 'menunggu_keputusan_customer']))
        <div class="rounded-xl bg-purple-500/10 border border-purple-500/20 p-6 mb-6">
            <h3 class="font-bold text-purple-300 mb-1">⏳ Pengajuan Refund dari Customer</h3>
            <p class="text-sm text-purple-300/80 mb-4">
                Customer sudah mengajukan pengembalian dana. Klik tombol di bawah untuk mengonfirmasi dan melanjutkan ke proses upload bukti transfer refund.
                @if($booking->refund_tujuan)
                    <br><span class="text-neutral-400 mt-1 block">Tujuan transfer: <span class="font-semibold text-white">{{ $booking->refund_tujuan }}</span></span>
                @endif
            </p>
            <form action="{{ route('admin.booking.confirm-refund', $booking) }}" method="POST"
                  data-confirm-message="Konfirmasi terima pengajuan refund dari {{ $booking->user->name }}?">
                @csrf
                <button type="submit"
                        class="bg-purple-600 hover:bg-purple-700 active:scale-[0.98] text-white font-bold px-6 py-3 rounded-lg transition">
                    ✅ Konfirmasi & Proses Refund
                </button>
            </form>
        </div>
    @endif

    {{-- Riwayat Pembayaran --}}
    <div class="rounded-xl bg-neutral-900 border border-white/10 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-white/10">
            <h2 class="font-display text-lg text-white tracking-wide">Riwayat Pembayaran</h2>
        </div>
        <div class="p-6">
            @if($booking->pembayarans->isEmpty())
                <p class="text-neutral-500 text-sm">Belum ada pembayaran.</p>
            @else
                <div class="space-y-3">
                    @foreach($booking->pembayarans as $idx => $payment)
                        <div class="border border-white/10 rounded-lg p-4 {{ $payment->status_verifikasi === 'diterima' ? 'border-emerald-500/20 bg-emerald-500/5' : ($payment->status_verifikasi === 'ditolak' ? 'border-red-500/20 bg-red-500/5' : 'bg-neutral-800') }}">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs text-neutral-400">
                                        Pembayaran #{{ $idx + 1 }} — {{ $payment->created_at->format('d M Y H:i') }}
                                        @if(!$payment->bukti_transfer)
                                            <span class="ml-2 bg-amber-500/10 text-amber-400 px-2 py-0.5 rounded-full text-xs font-bold border border-amber-500/20">CASH</span>
                                        @endif
                                    </p>
                                    <p class="font-bold text-white text-xl mt-1">Rp {{ number_format($payment->nominal, 0, ',', '.') }}</p>
                                </div>
                                <span @class([
                                    'px-2.5 py-1 rounded-full text-xs font-semibold border',
                                    'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' => $payment->status_verifikasi === 'diterima',
                                    'bg-amber-500/10 text-amber-400 border-amber-500/20' => $payment->status_verifikasi === 'pending',
                                    'bg-red-500/10 text-red-400 border-red-500/20' => $payment->status_verifikasi === 'ditolak',
                                ])>
                                    {{ $payment->status_verifikasi === 'pending' ? '⏳ Pending' : ($payment->status_verifikasi === 'diterima' ? '✅ Diterima' : '❌ Ditolak') }}
                                </span>
                            </div>

                            @if($payment->bukti_transfer)
                                <div class="mt-3">
                                    <img src="{{ Storage::url($payment->bukti_transfer) }}" alt="Bukti Transfer" class="w-32 h-32 object-cover rounded-lg border border-white/10">
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Informasi Refund (Sudah Diproses) --}}
    @if($booking->status_booking === 'direfund')
        <div class="rounded-xl bg-sky-500/10 border border-sky-500/30 overflow-hidden mb-6 shadow-lg shadow-sky-900/20 backdrop-blur-xl">
            <div class="px-6 py-4 border-b border-sky-500/20 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-sky-500/20 flex items-center justify-center text-xl">💸</div>
                <div>
                    <h2 class="font-display text-lg text-sky-200 tracking-wide">Refund Sudah Diteruskan ke Customer</h2>
                    <p class="text-xs text-sky-400/70">Bukti transfer sudah dicatat dan customer bisa mengunduhnya di halaman detail booking.</p>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                        <label class="block text-xs text-sky-400/70 mb-1 font-semibold uppercase tracking-wide">Nominal Refund</label>
                        <p class="text-white text-2xl font-black">Rp {{ number_format($booking->nominal_refund ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                        <label class="block text-xs text-sky-400/70 mb-1 font-semibold uppercase tracking-wide">Tanggal Refund</label>
                        <p class="text-white font-semibold">{{ $booking->tanggal_refund?->isoFormat('D MMMM YYYY, HH:mm') ?? '-' }}</p>
                    </div>
                </div>
                @if($booking->refund_tujuan)
                    <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                        <label class="block text-xs text-sky-400/70 mb-1 font-semibold uppercase tracking-wide">Ditransfer Ke</label>
                        <p class="text-white font-semibold">{{ $booking->refund_tujuan }}</p>
                    </div>
                @endif
                @if($booking->catatan_refund)
                    <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                        <label class="block text-xs text-sky-400/70 mb-2 font-semibold uppercase tracking-wide">Catatan Admin</label>
                        <p class="text-neutral-200 text-sm whitespace-pre-wrap">{{ $booking->catatan_refund }}</p>
                    </div>
                @endif
                @if($booking->bukti_refund_url)
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-white/5 rounded-xl p-4 border border-white/10">
                        <div>
                            <label class="block text-xs text-sky-400/70 mb-1 font-semibold uppercase tracking-wide">Bukti Transfer Refund</label>
                            <p class="text-sm text-neutral-300">Dokumen bukti transfer dari admin ke customer.</p>
                        </div>
                        <a href="{{ $booking->bukti_refund_url }}" target="_blank"
                           class="bg-sky-500 hover:bg-sky-600 text-white text-sm font-bold px-5 py-2.5 rounded-lg transition inline-flex items-center gap-2 shadow-lg shadow-sky-500/20">
                            📄 Lihat / Unduh Bukti
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Proses Refund (Form Upload Bukti Transfer) --}}
    @if($booking->bisa_direfund)
        @php
            $nominalDefault = (int) $booking->total_dibayar;
            $refundTujuanSudahAda = filled($booking->refund_tujuan);
        @endphp

        <div class="rounded-xl bg-gradient-to-br from-sky-500/10 via-white/5 to-purple-500/10 border border-sky-500/20 overflow-hidden mb-6 shadow-lg shadow-sky-900/10 backdrop-blur-xl">
            <div class="px-6 py-4 border-b border-white/10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-sky-500/20 flex items-center justify-center text-xl">⚙️</div>
                <div>
                    <h2 class="font-display text-lg text-white tracking-wide">Proses Refund — Upload Bukti Transfer</h2>
                    <p class="text-xs text-neutral-400">Admin sudah transfer refund di luar sistem? Upload bukti transfer di sini untuk dicatat dan memberi notifikasi ke customer.</p>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.booking.refund.store', $booking) }}" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf

                @if($refundTujuanSudahAda)
                    <div class="bg-emerald-500/5 border border-emerald-500/20 rounded-xl p-4">
                        <div class="flex items-start gap-2.5 mb-1">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold mt-0.5">✓</span>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-emerald-300">
                                Tujuan Transfer (Sudah Diisi Customer Saat Mengajukan Refund)
                            </label>
                        </div>
                        <div class="pl-7.5 ml-0">
                            <p class="text-white font-bold text-sm">{{ $booking->refund_tujuan }}</p>
                            <p class="text-[11px] text-neutral-500 mt-1">Data di atas dikirim langsung oleh customer, admin tidak perlu mengubahnya.</p>
                        </div>
                    </div>
                @else
                    <div class="bg-rose-500/5 border border-rose-500/20 rounded-xl p-4">
                        <div class="flex items-start gap-2.5 mb-2">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-rose-500/20 text-rose-300 text-xs font-bold mt-0.5">!</span>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-rose-300">
                                Customer Belum Memberikan Info Tujuan Transfer — Isi Manual <span class="text-rose-400">*</span>
                            </label>
                        </div>
                        <p class="text-[11px] text-rose-400/80 mb-3 ml-[28px]">Booking ini diajukan sebelum fitur self-service aktif, atau tujuan transfer tidak tercatat. Hubungi customer langsung (<span class="font-semibold text-neutral-300">{{ $booking->user->email }}</span>) untuk menanyakan rekening/e-wallet tujuan refund, lalu isikan hasilnya di bawah ini:</p>
                        <div class="ml-[28px]">
                            <input type="text" name="refund_tujuan" id="refund_tujuan"
                                   value="{{ old('refund_tujuan') }}"
                                   placeholder="Contoh: BCA 731098xxxx a.n. Ahmad Fauzi — atau OVO 08123456xxxx a.n. Ahmad Fauzi"
                                   required minlength="8" maxlength="255"
                                   class="w-full bg-neutral-950 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-rose-500 focus:ring-1 focus:ring-rose-500/50 outline-none transition placeholder:text-neutral-600">
                            @error('refund_tujuan')
                                <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-neutral-400 uppercase tracking-wider mb-1.5">
                            Nominal Refund (Rp) <span class="text-rose-400">*</span>
                        </label>
                        <p class="text-[11px] text-neutral-500 mb-2">Maksimal: <span class="text-emerald-400 font-semibold">Rp {{ number_format($nominalDefault, 0, ',', '.') }}</span> (total yang sudah dibayar customer). Bisa isi lebih sedikit untuk refund sebagian.</p>
                        <input type="number" name="nominal_refund" id="nominal_refund"
                               value="{{ old('nominal_refund', $nominalDefault) }}"
                               min="1" max="{{ $nominalDefault }}" required
                               class="w-full bg-neutral-950 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500/50 outline-none transition font-mono">
                        @error('nominal_refund')
                            <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-neutral-400 uppercase tracking-wider mb-1.5">
                            Bukti Transfer <span class="text-rose-400">*</span>
                        </label>
                        <p class="text-[11px] text-neutral-500 mb-2">Format: PNG/JPG/JPEG/PDF · Maks 5MB · Screenshot dari m-banking / e-wallet / ATM.</p>
                        <label for="buktiRefundInput"
                               class="block w-full border-2 border-dashed border-white/15 hover:border-sky-500 hover:bg-sky-500/5 rounded-xl px-4 py-5 cursor-pointer transition text-center">
                            <div id="buktiRefundPlaceholder">
                                <div class="text-3xl mb-1.5">📷</div>
                                <div class="text-sm font-semibold text-neutral-300">Klik untuk pilih file bukti</div>
                                <div class="text-xs text-neutral-500 mt-0.5">atau drag & drop file ke sini</div>
                            </div>
                            <div id="buktiRefundFileInfo" class="hidden text-left">
                                <div class="text-sm font-semibold text-emerald-400">✅ <span id="buktiRefundFileName">-</span></div>
                                <div class="text-xs text-neutral-500 mt-1">Klik untuk ganti file</div>
                            </div>
                            <input type="file" id="buktiRefundInput" name="bukti_refund"
                                   accept="image/png,image/jpeg,image/jpg,application/pdf" required class="hidden">
                        </label>
                        @error('bukti_refund')
                            <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                        <p id="buktiRefundFileError" class="text-rose-400 text-xs mt-1.5 hidden"></p>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-neutral-400 uppercase tracking-wider mb-1.5">
                        Catatan / Alasan Refund <span class="text-neutral-500">(Opsional)</span>
                    </label>
                    <textarea name="catatan_refund" id="catatan_refund" rows="3"
                              placeholder="Contoh: Refund penuh karena cuaca hujan. Customer minta refund ke rekening BRI."
                              class="w-full bg-neutral-950 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500/50 outline-none transition resize-none">{{ old('catatan_refund') }}</textarea>
                    @error('catatan_refund')
                        <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 pt-2">
                    <div class="text-[11px] text-neutral-500 max-w-lg">
                        ⚠️ Setelah submit, status booking otomatis berubah menjadi <span class="text-sky-300 font-semibold">"Direfund"</span> dan customer mendapatkan notifikasi beserta link untuk mengunduh bukti transfer. Pastikan nominal & bukti transfer sudah sesuai sebelum mengirim.
                    </div>
                    <button type="submit"
                            class="bg-sky-500 hover:bg-sky-600 active:scale-[0.98] text-white font-bold px-6 py-3 rounded-xl transition inline-flex items-center justify-center gap-2 shadow-lg shadow-sky-500/25 whitespace-nowrap w-full sm:w-auto">
                        <span>💸</span>
                        <span>Konfirmasi & Kirim Refund</span>
                    </button>
                </div>
            </form>
        </div>

        @push('scripts')
        <script>
            (function () {
                const input = document.getElementById('buktiRefundInput');
                const errEl = document.getElementById('buktiRefundFileError');
                const placeholder = document.getElementById('buktiRefundPlaceholder');
                const info = document.getElementById('buktiRefundFileInfo');
                const infoName = document.getElementById('buktiRefundFileName');
                if (!input) return;
                const MAX = 5 * 1024 * 1024;
                const ALLOWED = ['image/png', 'image/jpeg', 'image/jpg', 'application/pdf'];

                function validate(file) {
                    if (!file) return false;
                    if (!ALLOWED.includes(file.type)) {
                        errEl.textContent = 'Format file tidak didukung. Pilih PNG, JPG, JPEG, atau PDF.';
                        errEl.classList.remove('hidden');
                        return false;
                    }
                    if (file.size > MAX) {
                        errEl.textContent = 'Ukuran file terlalu besar (maks 5MB).';
                        errEl.classList.remove('hidden');
                        return false;
                    }
                    errEl.classList.add('hidden');
                    return true;
                }

                function updateUI(file) {
                    if (file && validate(file)) {
                        infoName.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                        placeholder.classList.add('hidden');
                        info.classList.remove('hidden');
                    } else {
                        input.value = '';
                        placeholder.classList.remove('hidden');
                        info.classList.add('hidden');
                    }
                }

                input.addEventListener('change', function () {
                    const f = input.files && input.files[0];
                    updateUI(f);
                });

                const labelWrap = input.closest('label');
                if (labelWrap) {
                    labelWrap.addEventListener('dragover', function (e) {
                        e.preventDefault();
                        labelWrap.classList.add('border-sky-500', 'bg-sky-500/5');
                    });
                    labelWrap.addEventListener('dragleave', function () {
                        labelWrap.classList.remove('border-sky-500', 'bg-sky-500/5');
                    });
                    labelWrap.addEventListener('drop', function (e) {
                        e.preventDefault();
                        labelWrap.classList.remove('border-sky-500', 'bg-sky-500/5');
                        const files = e.dataTransfer.files;
                        if (files && files.length) {
                            const dt = new DataTransfer();
                            dt.items.add(files[0]);
                            input.files = dt.files;
                            updateUI(input.files[0]);
                        }
                    });
                }

                const form = input.closest('form');
                if (form) {
                    form.addEventListener('submit', function (e) {
                        const f = input.files && input.files[0];
                        if (!validate(f)) {
                            e.preventDefault();
                            updateUI(null);
                            window.scrollTo({ top: form.offsetTop - 40, behavior: 'smooth' });
                            return;
                        }
                        const btn = form.querySelector('button[type="submit"]');
                        if (btn) {
                            btn.disabled = true;
                            btn.classList.add('opacity-70', 'cursor-wait');
                            btn.querySelector('span:last-child').textContent = 'Memproses…';
                        }
                    });
                }
            })();
        </script>
        @endpush
    @endif
</x-admin-layout>
