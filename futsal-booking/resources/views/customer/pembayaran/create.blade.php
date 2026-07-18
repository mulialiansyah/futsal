<x-app-layout>
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('customer.booking.show', $booking) }}"
           class="text-white/70 hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h2 class="text-2xl sm:text-3xl font-bold text-white">
            Upload Bukti Pembayaran
        </h2>
    </div>

    <div class="max-w-2xl mx-auto space-y-6">

        <!-- Info Booking -->
        <div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl p-6">
            <h3 class="font-bold text-white mb-4 text-sm uppercase tracking-wide text-neutral-400">
                Detail Booking
            </h3>
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
                          data-countdown="{{ $booking->payment_deadline->timestamp">
                        {{ $booking->sisa_waktu_format ?? '00:00:00' }}
                    </span>
                </div>
            @endif
        </div>

        <!-- Info Rekening -->
        <div class="bg-blue-500/20 border border-blue-500/30 rounded-2xl p-6">
            <h3 class="font-bold text-blue-300 mb-4">📋 Info Transfer</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-blue-200">Bank</span>
                    <span class="font-bold text-white">BCA</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-blue-200">No. Rekening</span>
                    <span class="font-bold text-white">1234567890</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-blue-200">Atas Nama</span>
                    <span class="font-bold text-white">FutsalKIte</span>
                </div>
                <div class="flex justify-between items-center border-t border-blue-500/30 pt-3 mt-3">
                    <span class="text-blue-200 font-semibold">Total Transfer</span>
                    <span class="font-extrabold text-white text-lg">
                        Rp {{ number_format($booking->sisa_tagihan, 0, ',', '.') }}
                    </span>
                </div>
            </div>
            <p class="text-xs text-blue-300/80 mt-4">
                * Transfer sesuai nominal di atas. Pembayaran berbeda nominal bisa memperlambat verifikasi.
            </p>
        </div>

        <!-- Form Upload -->
        <div class="bg-white/10 border border-white/20 backdrop-blur-xl rounded-2xl p-6">
            <h3 class="font-bold text-white mb-4">Upload Bukti Transfer</h3>

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

                <!-- Nominal -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-white mb-2">
                        Nominal yang Ditransfer (Rp) <span class="text-red-400">*</span>
                    </label>
                    <input type="number" name="nominal" id="inputNominal"
                           value="{{ old('nominal', $booking->sisa_tagihan) }}"
                           min="1" required
                           class="w-full bg-white/5 border border-white/20 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition @error('nominal') border-red-500/50 focus:ring-red-500 @enderror">
                    
                    @if($booking->status_booking === 'pending' && $booking->pembayarans->isEmpty())
                        <div class="flex flex-wrap gap-2 mt-3">
                            <button type="button" onclick="setNominal({{ $booking->total_harga / 2 }})"
                                    class="text-xs bg-blue-500/20 hover:bg-blue-500/30 text-blue-300 border border-blue-500/30 px-4 py-2 rounded-xl transition">
                                Set DP 50% (Rp {{ number_format($booking->total_harga / 2, 0, ',', '.') }})
                            </button>
                            <button type="button" onclick="setNominal({{ $booking->total_harga }})"
                                    class="text-xs bg-green-500/20 hover:bg-green-500/30 text-green-300 border border-green-500/30 px-4 py-2 rounded-xl transition">
                                Set Lunas 100% (Rp {{ number_format($booking->total_harga, 0, ',', '.') }})
                            </button>
                        </div>
                    @endif

                    <p class="text-xs text-neutral-400 mt-3">
                        Isi sesuai nominal yang sudah kamu transfer.
                    </p>
                    @error('nominal')
                        <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                    @enderror
                    <p id="nominalError" class="text-red-400 text-xs mt-2 hidden"></p>
                </div>

                <script>
                    function setNominal(amount) {
                        document.getElementById('inputNominal').value = amount;
                        document.getElementById('nominalError').classList.add('hidden');
                    }
                </script>

                <!-- Upload Foto -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-white mb-2">
                        Foto Bukti Transfer <span class="text-red-400">*</span>
                    </label>
                    <p class="text-xs text-neutral-400 mb-4">
                        Screenshot atau foto struk transfer. Format: JPG, PNG. Maks 5MB.
                    </p>

                    <!-- Drop Zone -->
                    <div id="dropZone"
                         class="border-2 border-dashed border-white/20 rounded-2xl p-10 text-center cursor-pointer hover:border-amber-400 hover:bg-amber-400/10 transition"
                         onclick="document.getElementById('buktiInput').click()">
                        <div class="text-5xl mb-3">📷</div>
                        <div class="text-sm font-semibold text-white">
                            Klik untuk upload bukti transfer
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
                            Preview bukti transfer
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
        // Preview bukti transfer
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

        // Clear nominal error when typing
        document.getElementById('inputNominal').addEventListener('input', function() {
            document.getElementById('nominalError').classList.add('hidden');
            this.classList.remove('border-red-500/50', 'focus:ring-red-500');
        });

        // Form Submit Frontend Validation
        document.getElementById('pembayaranForm').addEventListener('submit', function(e) {
            let isValid = true;

            // 1. Validasi Nominal
            const nominalInput = document.getElementById('inputNominal');
            const nominalError = document.getElementById('nominalError');
            if (!nominalInput.value || parseInt(nominalInput.value) <= 0) {
                nominalError.textContent = 'Nominal transfer wajib diisi dan harus lebih dari 0.';
                nominalError.classList.remove('hidden');
                nominalInput.classList.add('border-red-500/50', 'focus:ring-red-500');
                isValid = false;
            } else {
                nominalError.classList.add('hidden');
                nominalInput.classList.remove('border-red-500/50', 'focus:ring-red-500');
            }

            // 2. Validasi Bukti Transfer
            const fileInput = document.getElementById('buktiInput');
            const buktiError = document.getElementById('buktiError');
            const dropZoneEl = document.getElementById('dropZone');
            if (fileInput.files.length === 0) {
                buktiError.textContent = 'Foto bukti transfer wajib diunggah.';
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
</x-app-layout>
