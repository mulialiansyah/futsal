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

    <div class="max-w-2xl mx-auto space-y-6">

        <!-- Info Booking -->
        <div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl p-6">
            <h3 class="font-bold text-white mb-4 text-sm uppercase tracking-wide text-neutral-400">
                Detail Booking
            </h3>
            <div class="flex items-center gap-3 mb-4 rounded-xl border border-white/10 bg-white/5 p-3">
                <img src="{{ $booking->lapangan->fotoUtama->url }}"
                     alt="{{ $booking->lapangan->nama_lapangan }}"
                     class="h-16 w-24 rounded-lg object-cover">
                <div>
                    <p class="text-xs text-neutral-400">Lapangan yang akan dibayar</p>
                    <p class="font-semibold text-white">{{ $booking->lapangan->nama_lapangan }}</p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                    <div class="text-xs text-neutral-400 mb-1">Lapangan</div>
                    <div class="font-semibold text-white">{{ $booking->lapangan->nama_lapangan }}</div>
                    <div class="text-xs text-neutral-500">{{ $booking->lapangan->kategori_label ?? '' }}</div>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                    <div class="text-xs text-neutral-400 mb-1">Tanggal & Jam</div>
                    <div class="font-semibold text-white">
                        {{ \Carbon\Carbon::parse($booking->tanggal_main)->isoFormat('D MMM YYYY') }}
                    </div>
                    <div class="text-xs text-neutral-500">
                        {{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }}
                    </div>
                </div>
                <div class="bg-green-500/20 border border-green-500/30 rounded-xl p-4 col-span-1 sm:col-span-2">
                    <div class="text-xs text-neutral-400 mb-1">Total yang harus dibayar (Sisa Tagihan)</div>
                    <div class="font-extrabold text-green-300 text-2xl">
                        Rp {{ number_format($booking->sisa_tagihan, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Countdown -->
            @if($booking->payment_deadline)
                <div class="mt-4 p-4 bg-yellow-500/20 border border-yellow-500/30 rounded-xl flex flex-col sm:flex-row items-center justify-between gap-2">
                    <span class="text-sm text-yellow-300">⏰ Sisa waktu pembayaran:</span>
                    <span class="font-mono font-bold text-yellow-300 text-xl"
                          data-countdown="{{ $booking->payment_deadline->timestamp }}">
                        {{ $booking->sisa_waktu_format ?? '00:00:00' }}
                    </span>
                </div>
            @endif
        </div>

        <!-- Pembayaran Midtrans -->
        @if(config('services.midtrans.client_key'))
            <div class="bg-red-500/10 border border-red-500/30 rounded-2xl p-6">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <h3 class="font-bold text-red-200">💳 Bayar melalui Midtrans</h3>
                        <p class="text-xs text-red-200/70 mt-1">Pilih QRIS, e-wallet, transfer bank, atau kartu pada halaman pembayaran aman Midtrans.</p>
                    </div>
                    <span class="shrink-0 px-2.5 py-1 rounded-full bg-red-500/20 text-red-200 text-[11px] font-bold">OTOMATIS</span>
                </div>

                @if($booking->status_booking === 'pending')
                    <div class="grid sm:grid-cols-2 gap-3">
                        <button type="button" onclick="openMidtrans({{ (int) ceil($booking->total_harga * 0.5) }}, this)"
                                class="bg-white/10 hover:bg-white/15 border border-white/20 text-white font-semibold px-4 py-3 rounded-xl text-sm transition">
                            Bayar DP 50%<br>
                            <span class="text-red-200">Rp {{ number_format(ceil($booking->total_harga * 0.5), 0, ',', '.') }}</span>
                        </button>
                        <button type="button" onclick="openMidtrans({{ $booking->sisa_tagihan }}, this)"
                                class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-3 rounded-xl text-sm transition">
                            Bayar Lunas<br>
                            <span class="text-red-100">Rp {{ number_format($booking->sisa_tagihan, 0, ',', '.') }}</span>
                        </button>
                    </div>
                @else
                    <button type="button" onclick="openMidtrans({{ $booking->sisa_tagihan }}, this)"
                            class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-3 rounded-xl text-sm transition">
                        Bayar Pelunasan Rp {{ number_format($booking->sisa_tagihan, 0, ',', '.') }}
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
                        Screenshot status pembayaran dari Midtrans. Format: JPG, PNG. Maks 5MB.
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
                           accept="image/*" class="hidden"
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

                <button type="submit"
                        class="w-full bg-amber-400 hover:bg-amber-500 text-neutral-900 font-bold py-4 rounded-xl transition text-sm shadow-lg shadow-amber-400/20">
                    ✅ Kirim Bukti Pembayaran
                </button>
            </form>
        </div>

    </div>

    <script>
        @if(config('services.midtrans.client_key'))
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
        }

        // Preview screenshot pembayaran
        function previewBukti(input) {
            const container = document.getElementById('previewContainer');
            const img = document.getElementById('previewImg');
            const dropZone = document.getElementById('dropZone');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    img.src = e.target.result;
                    container.classList.remove('hidden');
                    dropZone.innerHTML = `
                        <div class="text-green-300 font-semibold text-sm">✅ ${input.files[0].name}</div>
                        <div class="text-xs text-neutral-400 mt-1">Klik untuk ganti foto</div>
                    `;
                    dropZone.classList.remove('border-red-500/50', 'bg-red-500/10');
                    document.getElementById('buktiError').classList.add('hidden');
                    dropZone.classList.add('border-green-500/50', 'bg-green-500/10');
                };
                reader.readAsDataURL(input.files[0]);
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
        document.getElementById('pembayaranForm').addEventListener('submit', function(e) {
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
                buktiError.classList.add('hidden');
                dropZoneEl.classList.remove('border-red-500/50', 'bg-red-500/10');
            }

            if (!isValid) {
                e.preventDefault(); // Stop form submission
            }
        });

        // Countdown timer
        const countdownEl = document.querySelector('[data-countdown]');
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
            setInterval(updateCountdown, 1000);
        }
    </script>
    @if(config('services.midtrans.client_key'))
        <script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
                data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    @endif
</x-app-layout>
