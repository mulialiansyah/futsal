<x-app-layout>
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('customer.booking.show', $booking) }}"
           class="text-white/70 hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h2 class="text-2xl sm:text-3xl font-bold text-white">
            Pembayaran Midtrans
        </h2>
    </div>

    <div class="max-w-xl mx-auto space-y-5">

        <!-- Ringkasan booking -->
        <div class="rounded-3xl border border-neutral-800 bg-neutral-900/90 p-5 sm:p-6 shadow-xl shadow-black/20">
            <div class="mb-4 flex items-start justify-between gap-3">
                <div>
                    <h3 class="text-lg font-extrabold text-white">Detail Booking</h3>
                    <p class="mt-1 text-xs text-neutral-500">{{ \Carbon\Carbon::parse($booking->created_at)->isoFormat('D MMM YYYY, HH:mm') }}</p>
                </div>
                <span class="rounded-full bg-emerald-500/10 px-2.5 py-1 text-[10px] font-bold tracking-wide text-emerald-400">MIDTRANS</span>
            </div>

            <div class="mb-4 flex gap-3 rounded-2xl bg-neutral-800 p-3.5">
                @if($booking->lapangan->fotoUtama)
                    <img src="{{ $booking->lapangan->fotoUtama->url }}"
                         alt="{{ $booking->lapangan->nama_lapangan }}"
                         class="h-24 w-28 shrink-0 rounded-xl object-cover sm:h-28 sm:w-32">
                @else
                    <div class="flex h-24 w-28 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-neutral-700 to-neutral-950 sm:h-28 sm:w-32">
                        <svg class="h-14 w-14" viewBox="0 0 100 100" aria-hidden="true">
                            <circle cx="50" cy="50" r="46" fill="#f4f4f4" stroke="#111" stroke-width="3"/>
                            <polygon points="50,28 61,36 57,49 43,49 39,36" fill="#111"/>
                            <g stroke="#111" stroke-width="2.5" fill="none">
                                <path d="M50 28 L50 12"/><path d="M61 36 L74 26"/><path d="M57 49 L68 60"/><path d="M43 49 L32 60"/><path d="M39 36 L26 26"/>
                            </g>
                        </svg>
                    </div>
                @endif
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-bold text-white">{{ $booking->lapangan->nama_lapangan }}</p>
                    <p class="mt-0.5 text-xs text-neutral-400">Booking Futsal · {{ $booking->lapangan->kategori_label ?? $booking->lapangan->kategori }}</p>
                    <div class="mt-2 flex flex-wrap gap-x-3 gap-y-1 text-[11px] text-neutral-400">
                        <span>📅 {{ \Carbon\Carbon::parse($booking->tanggal_main)->isoFormat('D MMM YYYY') }}</span>
                        <span>🕒 {{ substr($booking->jam_mulai, 0, 5) }}–{{ substr($booking->jam_selesai, 0, 5) }}</span>
                    </div>
                </div>
            </div>

            <div class="space-y-2.5">
                <div class="flex items-center justify-between rounded-2xl bg-neutral-800 px-4 py-3.5">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-500/15 text-base">💳</span>
                        <span class="text-sm font-semibold text-neutral-200">Total Tagihan</span>
                    </div>
                    <span class="text-sm font-extrabold text-emerald-400">Rp {{ number_format($booking->sisa_tagihan, 0, ',', '.') }}</span>
                </div>
            @if($booking->payment_deadline && $booking->status_booking !== 'lunas')
                <div class="flex items-center justify-between rounded-2xl bg-neutral-800 px-4 py-3.5">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-amber-400/15 text-base">⏱</span>
                        <span class="text-sm font-semibold text-neutral-200">Sisa Waktu Bayar</span>
                    </div>
                    <span class="font-mono text-sm font-extrabold text-amber-300" data-countdown="{{ $booking->payment_deadline->timestamp }}">{{ $booking->sisa_waktu_format ?? '00:00:00' }}</span>
                </div>
            @endif
            </div>
        </div>

        <!-- Pilihan pembayaran Midtrans -->
        @if(config('services.midtrans.client_key'))
            <div>
                <div class="mb-3 flex items-end justify-between gap-3">
                    <div>
                        <h3 class="font-bold text-white">Pilih Metode Pembayaran</h3>
                        <p class="mt-1 text-xs text-neutral-400">QRIS, e-wallet, transfer bank, atau kartu.</p>
                    </div>
                </div>
                @if($booking->status_booking === 'pending')
                    <div class="grid gap-3 sm:grid-cols-2">
                        <button type="button" data-payment-option data-nominal="{{ (int) ceil($booking->total_harga * 0.5) }}" onclick="pilihPembayaran(this)" class="payment-option relative min-h-48 overflow-hidden rounded-2xl bg-amber-400 p-5 text-left text-neutral-950 transition hover:-translate-y-0.5 hover:shadow-lg hover:shadow-amber-400/20 focus:outline-none focus:ring-2 focus:ring-white">
                            <span class="absolute -right-6 -top-2 h-5 w-24 rotate-45 bg-black/15"></span><span class="absolute bottom-3 right-8 h-16 w-5 rotate-45 bg-black/15"></span>
                            <span class="relative block text-sm font-extrabold">Bayar DP 50%</span>
                            <span class="relative mt-2 block text-2xl font-black">Rp {{ number_format(ceil($booking->total_harga * 0.5), 0, ',', '.') }}</span>
                            <span class="relative mt-1 block text-xs text-neutral-800/75">Lunasi sisanya di lokasi</span>
                            <span class="relative mt-5 block rounded-xl bg-neutral-950 py-2.5 text-center text-sm font-extrabold text-white">Pilih</span>
                        </button>
                        <button type="button" data-payment-option data-nominal="{{ $booking->sisa_tagihan }}" onclick="pilihPembayaran(this)" class="payment-option relative min-h-48 overflow-hidden rounded-2xl bg-red-500 p-5 text-left text-white transition hover:-translate-y-0.5 hover:shadow-lg hover:shadow-red-500/20 focus:outline-none focus:ring-2 focus:ring-white">
                            <span class="absolute -right-6 -top-2 h-5 w-24 rotate-45 bg-black/15"></span><span class="absolute bottom-3 right-8 h-16 w-5 rotate-45 bg-black/15"></span>
                            <span class="relative block text-sm font-extrabold">Bayar Lunas</span>
                            <span class="relative mt-2 block text-2xl font-black">Rp {{ number_format($booking->sisa_tagihan, 0, ',', '.') }}</span>
                            <span class="relative mt-1 block text-xs text-white/80">Langsung lunas, tanpa sisa tagihan</span>
                            <span class="relative mt-5 block rounded-xl bg-white py-2.5 text-center text-sm font-extrabold text-neutral-900">Pilih</span>
                        </button>
                    </div>
                    <button type="button" id="lanjutBayarBtn" disabled onclick="lanjutPembayaran(this)" class="mt-5 w-full rounded-xl bg-white py-3.5 text-sm font-extrabold text-neutral-900 opacity-50 transition hover:bg-neutral-200 disabled:cursor-not-allowed">
                        Pilih nominal pembayaran terlebih dahulu
                    </button>
                @else
                    <button type="button" onclick="openMidtrans({{ $booking->sisa_tagihan }}, this)"
                            class="relative w-full overflow-hidden rounded-2xl bg-red-500 px-5 py-5 text-left text-white transition hover:bg-red-600">
                        <span class="absolute -right-6 -top-2 h-5 w-24 rotate-45 bg-black/15"></span>
                        <span class="relative block text-sm font-extrabold">Bayar Pelunasan</span>
                        <span class="relative mt-1 block text-2xl font-black">Rp {{ number_format($booking->sisa_tagihan, 0, ',', '.') }}</span>
                        <span class="relative mt-4 block rounded-xl bg-white py-2.5 text-center text-sm font-extrabold text-neutral-900">
                        Bayar Pelunasan Rp {{ number_format($booking->sisa_tagihan, 0, ',', '.') }}
                        </span>
                    </button>
                @endif

                <p id="midtransError" class="hidden text-sm text-red-200 mt-3" role="alert"></p>
            </div>
        @endif

        <!-- Screenshot bukti Midtrans -->
        <div id="buktiMidtransSection" class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl p-6 {{ $midtransPayment ? '' : 'hidden' }}">
            <h3 class="font-bold text-white mb-2">Upload Screenshot Pembayaran</h3>
            <p class="text-xs text-neutral-400 mb-4">Setelah pembayaran selesai di Midtrans, upload screenshot status pembayarannya di sini. Screenshot ini terhubung ke transaksi Midtrans yang sama.</p>

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-500/20 border border-red-500/30 rounded-xl text-sm text-red-300">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" id="pembayaranForm"
                  action="{{ route('customer.pembayaran.store', $booking) }}"
                  enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="midtrans_order_id" id="midtransOrderId" value="{{ old('midtrans_order_id', $midtransPayment?->midtrans_order_id) }}">

                <!-- Upload Foto -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-white mb-2">
                        Screenshot Pembayaran Midtrans <span class="text-red-400">*</span>
                    </label>
                    <p class="text-xs text-neutral-400 mb-4">
                        Screenshot status pembayaran dari Midtrans. Format: JPG, PNG. Maks 2MB.
                    </p>

                    <!-- Drop Zone -->
                    <div id="dropZone"
                         class="border-2 border-dashed border-white/20 rounded-2xl p-10 text-center cursor-pointer hover:border-amber-400 hover:bg-amber-400/10 transition"
                         onclick="document.getElementById('buktiInput').click()">
                        <div class="text-5xl mb-3">📷</div>
                        <div class="text-sm font-semibold text-white">
                            Klik untuk upload screenshot
                        </div>
                        <div class="text-xs text-neutral-400 mt-1">atau drag & drop di sini</div>
                    </div>

                    <input type="file" id="buktiInput" name="bukti_transfer"
                           accept="image/png, image/jpeg, image/jpg" class="hidden"
                           onchange="previewBukti(this)">

                    <!-- Preview -->
                    <div id="previewContainer" class="hidden mt-4">
                        <img id="previewImg"
                             class="max-w-full max-h-64 rounded-xl border border-white/10 object-contain mx-auto">
                        <p class="text-xs text-neutral-400 text-center mt-2">
                            Preview screenshot pembayaran
                        </p>
                    </div>

                    @error('bukti_transfer')
                        <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                    @enderror
                    <p id="buktiError" class="text-red-400 text-xs mt-2 hidden"></p>
                </div>

                <button type="submit" id="kirimBuktiBtn" disabled
                        class="w-full bg-amber-400 hover:bg-amber-500 disabled:bg-neutral-800 disabled:text-neutral-500 disabled:hover:bg-neutral-800 disabled:cursor-not-allowed text-neutral-900 font-bold py-4 rounded-xl transition text-sm shadow-lg shadow-amber-400/20 disabled:shadow-none">
                    <span id="kirimBuktiLabel">Upload screenshot pembayaran terlebih dahulu</span>
                </button>
            </form>
        </div>

    </div>

    <script>
        @if(config('services.midtrans.client_key'))
            let nominalTerpilih = null;

            function pilihPembayaran(button) {
                nominalTerpilih = Number(button.dataset.nominal);
                document.querySelectorAll('[data-payment-option]').forEach(option => {
                    option.classList.remove('ring-2', 'ring-white', 'scale-[0.98]');
                });
                button.classList.add('ring-2', 'ring-white', 'scale-[0.98]');

                const lanjutBtn = document.getElementById('lanjutBayarBtn');
                lanjutBtn.disabled = false;
                lanjutBtn.classList.remove('opacity-50');
                lanjutBtn.textContent = `Lanjut Bayar Rp ${nominalTerpilih.toLocaleString('id-ID')}`;
            }

            function lanjutPembayaran(button) {
                if (nominalTerpilih) {
                    openMidtrans(nominalTerpilih, button);
                }
            }

            function showMidtransError(message) {
                const error = document.getElementById('midtransError');
                error.textContent = message;
                error.classList.remove('hidden');
            }

            async function openMidtrans(nominal, button) {
                const originalText = button.innerHTML;
                button.disabled = true;
                button.innerHTML = 'Menyiapkan pembayaran…';
                document.getElementById('midtransError').classList.add('hidden');

                try {
                    const response = await fetch('{{ route('customer.pembayaran.midtrans', $booking) }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify({ nominal }),
                    });
                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(Object.values(data.errors ?? {}).flat().join(' ') || data.message || 'Tidak dapat membuat transaksi Midtrans.');
                    }

                    window.snap.pay(data.snap_token, {
                        onSuccess: () => showBuktiMidtrans(data.order_id),
                        onPending: () => showBuktiMidtrans(data.order_id),
                        onError: () => showMidtransError('Pembayaran gagal diproses. Silakan coba kembali.'),
                        onClose: () => showMidtransError('Jendela pembayaran ditutup. Anda dapat melanjutkan pembayaran sebelum batas waktu berakhir.'),
                    });
                } catch (error) {
                    showMidtransError(error.message);
                } finally {
                    button.disabled = false;
                    button.innerHTML = originalText;
                }
            }
        @endif

        function showBuktiMidtrans(orderId) {
            document.getElementById('midtransOrderId').value = orderId;
            document.getElementById('buktiMidtransSection').classList.remove('hidden');
            document.getElementById('buktiMidtransSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
            updateKirimBuktiState();
        }

        function updateKirimBuktiState() {
            const fileInput = document.getElementById('buktiInput');
            const btn = document.getElementById('kirimBuktiBtn');
            const label = document.getElementById('kirimBuktiLabel');
            if (!btn || !label) return;
            const adaFile = !!(fileInput && fileInput.files && fileInput.files.length > 0);
            if (adaFile) {
                btn.disabled = false;
                label.innerHTML = '✅ Kirim Bukti Pembayaran';
            } else {
                btn.disabled = true;
                label.innerHTML = 'Upload screenshot pembayaran terlebih dahulu';
            }
        }

        // Preview screenshot pembayaran
        function previewBukti(input) {
            const container = document.getElementById('previewContainer');
            const img = document.getElementById('previewImg');
            const dropZone = document.getElementById('dropZone');
            const buktiError = document.getElementById('buktiError');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
                const maxSize = 2 * 1024 * 1024; // 2MB

                if (!allowedTypes.includes(file.type)) {
                    buktiError.textContent = 'File harus berformat PNG atau JPG.';
                    buktiError.classList.remove('hidden');
                    dropZone.classList.add('border-red-500/50', 'bg-red-500/10');
                    container.classList.add('hidden');
                    input.value = '';
                    updateKirimBuktiState();
                    return;
                }

                if (file.size > maxSize) {
                    buktiError.textContent = 'Ukuran file maksimal 2MB.';
                    buktiError.classList.remove('hidden');
                    dropZone.classList.add('border-red-500/50', 'bg-red-500/10');
                    container.classList.add('hidden');
                    input.value = '';
                    updateKirimBuktiState();
                    return;
                }

                const reader = new FileReader();
                reader.onload = e => {
                    img.src = e.target.result;
                    container.classList.remove('hidden');
                    dropZone.innerHTML = `
                        <div class="text-green-300 font-semibold text-sm">✅ ${file.name}</div>
                        <div class="text-xs text-neutral-400 mt-1">Klik untuk ganti foto</div>
                    `;
                    dropZone.classList.remove('border-red-500/50', 'bg-red-500/10');
                    buktiError.classList.add('hidden');
                    dropZone.classList.add('border-green-500/50', 'bg-green-500/10');
                    updateKirimBuktiState();
                };
                reader.readAsDataURL(file);
            } else {
                updateKirimBuktiState();
            }
        }

        // Drag & drop
        const dz = document.getElementById('dropZone');
        dz.addEventListener('dragover', e => { e.preventDefault(); dz.classList.add('border-amber-400', 'bg-amber-400/10'); });
        dz.addEventListener('dragleave', () => dz.classList.remove('border-amber-400', 'bg-amber-400/10'));
        dz.addEventListener('drop', e => {
            e.preventDefault();
            dz.classList.remove('border-amber-400', 'bg-amber-400/10');
            const files = e.dataTransfer.files;
            if (files.length) {
                const input = document.getElementById('buktiInput');
                const dt = new DataTransfer();
                dt.items.add(files[0]);
                input.files = dt.files;
                previewBukti(input);
            }
        });

        // Form Submit Frontend Validation
        const pembayaranForm = document.getElementById('pembayaranForm');
        pembayaranForm.addEventListener('submit', function(e) {
            let isValid = true;

            // Validasi screenshot pembayaran
            const fileInput = document.getElementById('buktiInput');
            const buktiError = document.getElementById('buktiError');
            const dropZoneEl = document.getElementById('dropZone');
            if (fileInput.files.length === 0) {
                buktiError.textContent = 'Screenshot pembayaran wajib diunggah.';
                buktiError.classList.remove('hidden');
                dropZoneEl.classList.add('border-red-500/50', 'bg-red-500/10');
                isValid = false;
            } else {
                const file = fileInput.files[0];
                const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
                const maxSize = 2 * 1024 * 1024; // 2MB

                if (!allowedTypes.includes(file.type)) {
                    buktiError.textContent = 'File harus berformat PNG atau JPG.';
                    buktiError.classList.remove('hidden');
                    dropZoneEl.classList.add('border-red-500/50', 'bg-red-500/10');
                    isValid = false;
                } else if (file.size > maxSize) {
                    buktiError.textContent = 'Ukuran file maksimal 2MB.';
                    buktiError.classList.remove('hidden');
                    dropZoneEl.classList.add('border-red-500/50', 'bg-red-500/10');
                    isValid = false;
                } else {
                    buktiError.classList.add('hidden');
                    dropZoneEl.classList.remove('border-red-500/50', 'bg-red-500/10');
                }
            }

            if (!isValid) {
                e.preventDefault();
                updateKirimBuktiState();
                return;
            }

            const submitBtn = document.getElementById('kirimBuktiBtn');
            const submitLabel = document.getElementById('kirimBuktiLabel');
            submitBtn.disabled = true;
            submitLabel.innerHTML = '⏳ Mengirim bukti pembayaran…';
            submitBtn.classList.add('opacity-70', 'cursor-wait');
        });

        window.addEventListener('DOMContentLoaded', function() {
            updateKirimBuktiState();
        });

        // Countdown timer
        let countdownInterval;
        const countdownEl = document.querySelector('[data-countdown]');
        @if($booking->status_booking !== 'lunas')
            if (countdownEl) {
                function updateCountdown() {
                    const deadline = parseInt(countdownEl.dataset.countdown) * 1000;
                    const sisa = deadline - new Date().getTime();
                    if (sisa <= 0) {
                        countdownEl.textContent = '00:00:00';
                        countdownEl.classList.add('text-red-400');
                        return;
                    }
                    const jam = Math.floor(sisa / 3600000);
                    const menit = Math.floor((sisa % 3600000) / 60000);
                    const detik = Math.floor((sisa % 60000) / 1000);
                    countdownEl.textContent = `${String(jam).padStart(2,'0')}:${String(menit).padStart(2,'0')}:${String(detik).padStart(2,'0')}`;
                }
                updateCountdown();
                countdownInterval = setInterval(updateCountdown, 1000);
            }
        @endif
    </script>
    @if(config('services.midtrans.client_key'))
        <script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
                data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    @endif
</x-app-layout>
