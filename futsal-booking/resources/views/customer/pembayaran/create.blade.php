<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('customer.booking.show', $booking) }}"
               class="text-gray-400 hover:text-gray-600 transition">←</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Upload Bukti Pembayaran
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- Info Booking --}}
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-bold text-gray-700 mb-3 text-sm uppercase tracking-wide">
                    Detail Booking
                </h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="text-xs text-gray-400 mb-1">Lapangan</div>
                        <div class="font-semibold text-gray-800">{{ $booking->lapangan->nama_lapangan }}</div>
                        <div class="text-xs text-gray-500">{{ $booking->lapangan->kategori_label ?? '' }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="text-xs text-gray-400 mb-1">Tanggal & Jam</div>
                        <div class="font-semibold text-gray-800">
                            {{ \Carbon\Carbon::parse($booking->tanggal_main)->isoFormat('D MMM YYYY') }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ substr($booking->jam_mulai, 0, 5) }} – {{ substr($booking->jam_selesai, 0, 5) }}
                        </div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-3 col-span-2">
                        <div class="text-xs text-gray-400 mb-1">Total yang harus dibayar (Sisa Tagihan)</div>
                        <div class="font-extrabold text-green-700 text-xl">
                            Rp {{ number_format($booking->sisa_tagihan, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                {{-- Countdown --}}
                @if($booking->payment_deadline)
                    <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg flex items-center justify-between">
                        <span class="text-sm text-yellow-700">⏰ Sisa waktu pembayaran:</span>
                        <span class="font-mono font-bold text-yellow-700 text-lg"
                              data-countdown="{{ $booking->payment_deadline->timestamp }}">
                            {{ $booking->sisa_waktu_format ?? '00:00:00' }}
                        </span>
                    </div>
                @endif
            </div>

            {{-- Info Rekening (statis, bisa diubah sesuai kebutuhan) --}}
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                <h3 class="font-bold text-blue-800 mb-3">📋 Info Transfer</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-blue-700">Bank</span>
                        <span class="font-bold text-blue-900">BCA</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-blue-700">No. Rekening</span>
                        <span class="font-bold text-blue-900">1234567890</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-blue-700">Atas Nama</span>
                        <span class="font-bold text-blue-900">FutsalKIte</span>
                    </div>
                    <div class="flex justify-between border-t border-blue-200 pt-2 mt-2">
                        <span class="text-blue-700 font-semibold">Total Transfer</span>
                        <span class="font-extrabold text-blue-900">
                            Rp {{ number_format($booking->sisa_tagihan, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                <p class="text-xs text-blue-600 mt-3">
                    * Transfer sesuai nominal di atas. Pembayaran berbeda nominal bisa memperlambat verifikasi.
                </p>
            </div>

            {{-- Form Upload --}}
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-bold text-gray-700 mb-4">Upload Bukti Transfer</h3>

                @if($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
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

                    {{-- Nominal --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Nominal yang Ditransfer (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="nominal" id="inputNominal"
                               value="{{ old('nominal', $booking->sisa_tagihan) }}"
                               min="1" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('nominal') border-red-400 @enderror">
                        
                        @if($booking->status_booking === 'pending' && $booking->pembayarans->isEmpty())
                            <div class="flex gap-2 mt-2">
                                <button type="button" onclick="setNominal({{ $booking->total_harga / 2 }})" 
                                        class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded border border-blue-300 transition">
                                    Set DP 50% (Rp {{ number_format($booking->total_harga / 2, 0, ',', '.') }})
                                </button>
                                <button type="button" onclick="setNominal({{ $booking->total_harga }})" 
                                        class="text-xs bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1 rounded border border-green-300 transition">
                                    Set Lunas 100% (Rp {{ number_format($booking->total_harga, 0, ',', '.') }})
                                </button>
                            </div>
                        @endif

                        <p class="text-xs text-gray-400 mt-2">
                            Isi sesuai nominal yang sudah kamu transfer.
                        </p>
                        @error('nominal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p id="nominalError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <script>
                        function setNominal(amount) {
                            document.getElementById('inputNominal').value = amount;
                            document.getElementById('nominalError').classList.add('hidden');
                        }
                    </script>

                    {{-- Upload Foto --}}
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Foto Bukti Transfer <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-gray-400 mb-3">
                            Screenshot atau foto struk transfer. Format: JPG, PNG. Maks 5MB.
                        </p>

                        {{-- Drop Zone --}}
                        <div id="dropZone"
                             class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer hover:border-green-400 hover:bg-green-50 transition"
                             onclick="document.getElementById('buktiInput').click()">
                            <div class="text-4xl mb-2">📷</div>
                            <div class="text-sm font-semibold text-gray-600">
                                Klik untuk upload bukti transfer
                            </div>
                            <div class="text-xs text-gray-400 mt-1">atau drag & drop di sini</div>
                        </div>

                        <input type="file" id="buktiInput" name="bukti_transfer"
                               accept="image/*" class="hidden"
                               onchange="previewBukti(this)">

                        {{-- Preview --}}
                        <div id="previewContainer" class="hidden mt-3">
                            <img id="previewImg"
                                 class="max-w-full max-h-64 rounded-xl border border-gray-200 object-contain mx-auto">
                            <p class="text-xs text-gray-400 text-center mt-2">
                                Preview bukti transfer
                            </p>
                        </div>

                        @error('bukti_transfer')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p id="buktiError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition text-sm">
                        ✅ Kirim Bukti Pembayaran
                    </button>
                </form>
            </div>

        </div>
    </div>

    <script>
        // Preview bukti transfer
        function previewBukti(input) {
            const container = document.getElementById('previewContainer');
            const img       = document.getElementById('previewImg');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    img.src = e.target.result;
                    container.classList.remove('hidden');
                    document.getElementById('dropZone').innerHTML = `
                        <div class="text-green-600 font-semibold text-sm">✅ ${input.files[0].name}</div>
                        <div class="text-xs text-gray-400 mt-1">Klik untuk ganti foto</div>
                    `;
                    document.getElementById('dropZone').classList.remove('border-red-500', 'bg-red-50');
                    document.getElementById('buktiError').classList.add('hidden');
                    document.getElementById('dropZone').classList.add('border-green-400', 'bg-green-50');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Drag & drop
        const dz = document.getElementById('dropZone');
        dz.addEventListener('dragover', e => { e.preventDefault(); dz.classList.add('border-green-400', 'bg-green-50'); });
        dz.addEventListener('dragleave', () => dz.classList.remove('border-green-400', 'bg-green-50'));
        dz.addEventListener('drop', e => {
            e.preventDefault();
            dz.classList.remove('border-green-400', 'bg-green-50');
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
            this.classList.remove('border-red-500', 'focus:ring-red-500');
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
                nominalInput.classList.add('border-red-500', 'focus:ring-red-500');
                isValid = false;
            } else {
                nominalError.classList.add('hidden');
                nominalInput.classList.remove('border-red-500', 'focus:ring-red-500');
            }

            // 2. Validasi Bukti Transfer
            const fileInput = document.getElementById('buktiInput');
            const buktiError = document.getElementById('buktiError');
            if (fileInput.files.length === 0) {
                buktiError.textContent = 'Foto bukti transfer wajib diunggah.';
                buktiError.classList.remove('hidden');
                document.getElementById('dropZone').classList.add('border-red-500', 'bg-red-50');
                isValid = false;
            } else {
                buktiError.classList.add('hidden');
                document.getElementById('dropZone').classList.remove('border-red-500', 'bg-red-50');
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
                    countdownEl.classList.add('text-red-600');
                    return;
                }
                const jam   = Math.floor(sisa / 3600000);
                const menit = Math.floor((sisa % 3600000) / 60000);
                const detik = Math.floor((sisa % 60000) / 1000);
                countdownEl.textContent = `${String(jam).padStart(2,'0')}:${String(menit).padStart(2,'0')}:${String(detik).padStart(2,'0')}`;
            }
            updateCountdown();
            setInterval(updateCountdown, 1000);
        }
    </script>
</x-app-layout>
