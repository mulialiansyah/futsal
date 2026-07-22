<x-app-layout>
    <div class="max-w-5xl mx-auto py-6">

        <!-- ===== GREETING ===== -->
        <div class="pt-2 pb-6">
            <h1 class="font-display text-2xl sm:text-3xl text-white">Halo, {{ auth()->user()->name ?? 'Penyewa' }} 👋</h1>
            <p class="text-neutral-400 text-sm mt-1">Pilih lapangan, atur jadwal, langsung main.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 text-red-300 rounded-xl text-sm">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('customer.booking.store') }}" method="POST" id="bookingForm">
            @csrf

            <!-- ===== PILIH LAPANGAN — KARTU FOTO ===== -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-sm font-bold text-white">Pilih Lapangan</label>
                    <span class="text-xs text-neutral-500">{{ $lapangans->count() }} lapangan tersedia</span>
                </div>

                {{-- Select tersembunyi untuk submit form --}}
                <select name="lapangan_id" id="lapanganSelect" required class="hidden">
                    <option value="">— Pilih lapangan —</option>
                    @foreach($lapangans as $lapangan)
                        <option value="{{ $lapangan->id }}"
                                data-kategori="{{ $lapangan->kategori }}"
                                {{ (old('lapangan_id') ?? request('lapangan_id')) == $lapangan->id ? 'selected' : '' }}>
                            {{ $lapangan->nama_lapangan }}
                        </option>
                    @endforeach
                </select>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3" id="lapanganCards">
                    @foreach($lapangans as $lapangan)
                        @php
                            $imgUrl = $lapangan->foto_utama
                                ? $lapangan->foto_utama->url
                                : 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?q=80&w=800&auto=format&fit=crop';
                            $isSelected = (old('lapangan_id') ?? request('lapangan_id')) == $lapangan->id;
                        @endphp
                        <button type="button"
                                class="lapangan-card text-left rounded-2xl overflow-hidden border-2 bg-neutral-900 transition {{ $isSelected ? 'border-amber-400' : 'border-white/10 hover:border-white/30' }}"
                                data-id="{{ $lapangan->id }}"
                                data-kategori="{{ $lapangan->kategori }}">
                            <div class="relative h-24 sm:h-28">
                                <img src="{{ $imgUrl }}" alt="{{ $lapangan->nama_lapangan }}" class="w-full h-full object-cover">
                                <span class="absolute top-2 left-2 px-2 py-0.5 rounded-full bg-black/60 backdrop-blur-sm text-[10px] font-bold text-white">
                                    {{ $lapangan->kategori_label }}
                                </span>
                                <div class="lapangan-check absolute top-2 right-2 w-5 h-5 rounded-full bg-amber-400 items-center justify-center {{ $isSelected ? 'flex' : 'hidden' }}">
                                    <svg class="w-3 h-3 text-neutral-950" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="p-2.5">
                                <div class="text-xs font-bold text-white truncate">{{ $lapangan->nama_lapangan }}</div>
                                <div class="text-[11px] text-neutral-500 truncate">{{ $lapangan->deskripsi_singkat }}</div>
                            </div>
                        </button>
                    @endforeach
                </div>

                @error('lapangan_id')
                    <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- ===== TANGGAL MAIN ===== -->
            <div class="mb-6">
                <label for="tanggal_main" class="block text-sm font-bold text-white mb-2">Tanggal Main</label>
                <input type="date" name="tanggal_main" id="tanggal_main" required
                       value="{{ old('tanggal_main') ?? request('tanggal_main') }}"
                       min="{{ now()->addDays(2)->toDateString() }}"
                       class="w-full bg-neutral-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('tanggal_main') border-red-500 @enderror">
                <p class="text-neutral-500 text-xs mt-1.5">* Minimal H-2 (2 hari sebelum main). Harga weekend/tanggal merah berbeda dengan weekday.</p>
                @error('tanggal_main')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Peringatan Penutupan Lapangan -->
            <div id="closureWarning" class="hidden mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-xl">
                <div class="flex items-start gap-3">
                    <span class="text-2xl">🚫</span>
                    <div>
                        <p class="font-bold text-red-300 text-sm">Lapangan Tidak Tersedia</p>
                        <p class="text-red-400 text-xs mt-1" id="closureMessage"></p>
                        <p class="text-red-400 text-xs mt-1 font-semibold" id="closurePeriod"></p>
                    </div>
                </div>
            </div>

            <!-- ===== JAM MULAI & DURASI ===== -->
            <div class="grid grid-cols-2 gap-4 mb-2">
                <div>
                    <label for="jamMulai" class="block text-sm font-bold text-white mb-2">Jam Mulai</label>
                    <input type="time" name="jam_mulai" id="jamMulai" required
                           value="{{ old('jam_mulai') ?? request('jam_mulai') }}"
                           min="08:00" max="21:00"
                           class="w-full bg-neutral-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('jam_mulai') border-red-500 @enderror">
                    <p class="text-neutral-500 text-xs mt-1.5">* Jam operasional 08:00 - 21:00</p>
                    @error('jam_mulai')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="durasiJam" class="block text-sm font-bold text-white mb-2">Durasi Main</label>
                    <select name="durasi_jam" id="durasiJam" required
                            class="w-full bg-neutral-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('durasi_jam') border-red-500 @enderror">
                        <option value="">— Pilih —</option>
                        @foreach([1, 2, 3, 4] as $jam)
                            <option value="{{ $jam }}" {{ old('durasi_jam') == $jam ? 'selected' : '' }}>{{ $jam }} Jam</option>
                        @endforeach
                    </select>
                    @error('durasi_jam')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Jam Selesai otomatis -->
            <div class="mb-6">
                <div class="bg-neutral-900 border border-white/10 rounded-xl px-4 py-3 flex justify-between items-center">
                    <span class="text-sm text-neutral-400">Jam Selesai (otomatis):</span>
                    <span class="font-bold text-white" id="jamSelesaiDisplay">—</span>
                </div>
            </div>

            <!-- ===== HARGA PREVIEW ===== -->
            <div class="bg-neutral-900 border border-white/10 rounded-2xl p-5 mb-8">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-sm text-neutral-400">Tipe Hari:</span>
                    <span class="text-sm font-semibold text-white" id="tipeHariDisplay">—</span>
                </div>
                <div class="flex justify-between items-center mb-3 border-t border-white/10 pt-3">
                    <span class="text-sm text-neutral-400">Estimasi Total Harga:</span>
                    <span class="text-lg font-extrabold text-white" id="totalHarga">Rp 0</span>
                </div>
                <div class="flex justify-between items-center bg-amber-400/10 border border-amber-400/20 p-3 rounded-xl">
                    <span class="text-xs text-amber-300 font-bold">Bisa DP Dulu (Min 50%):</span>
                    <span class="text-sm font-extrabold text-amber-300" id="dpHarga">Rp 0</span>
                </div>
                <p class="text-neutral-500 text-xs mt-3">* Harga final dihitung ulang otomatis oleh sistem. Waktu pembayaran 1 jam setelah booking.</p>
            </div>

            <div class="flex justify-end">
                <button type="submit" id="submitBtn"
                        class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white font-bold px-8 py-3.5 rounded-full shadow-lg shadow-red-600/30 transition duration-200 text-center">
                    Booking Sekarang
                </button>
            </div>
        </form>
    </div>

    <!-- ===== FLOATING CHAT KE ADMIN ===== -->
    <a href="https://wa.me/62895610031040?text={{ urlencode('Halo min, saya mau tanya soal booking lapangan.') }}"
       target="_blank" rel="noopener"
       class="fixed right-5 bottom-24 z-40 w-14 h-14 rounded-full bg-emerald-500 hover:bg-emerald-600 shadow-lg shadow-emerald-500/30 flex items-center justify-center transition"
       title="Chat Admin via WhatsApp">
        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.48 1.32 5L2 22l5.25-1.38a9.9 9.9 0 004.79 1.22h.01c5.46 0 9.9-4.45 9.9-9.9 0-2.65-1.03-5.14-2.9-7.01A9.87 9.87 0 0012.04 2zm5.8 14.16c-.24.68-1.4 1.32-1.93 1.4-.5.08-1.12.11-1.8-.11-.42-.13-.95-.31-1.64-.6-2.88-1.24-4.76-4.14-4.9-4.33-.14-.19-1.17-1.56-1.17-2.98 0-1.42.74-2.11 1-2.4.26-.29.57-.36.76-.36.19 0 .38 0 .55.01.18.01.41-.07.64.49.24.58.81 2 .88 2.15.07.15.12.32.02.51-.1.19-.15.31-.3.48-.14.17-.3.37-.43.5-.14.14-.29.29-.13.57.17.29.75 1.24 1.62 2.01 1.11.99 2.05 1.3 2.34 1.44.29.14.46.12.63-.07.17-.19.72-.84.91-1.13.19-.29.38-.24.64-.15.26.1 1.66.79 1.94.93.29.14.48.22.55.34.07.13.07.71-.17 1.39z"/>
        </svg>
    </a>

    <script>
        const TARIFS     = @json($tarifs);
        const HOLIDAYS   = @json($holidays);
        const PENUTUPANS = @json($penutupans);

        // === Sinkronisasi kartu foto lapangan dengan select tersembunyi ===
        document.querySelectorAll('.lapangan-card').forEach(card => {
            card.addEventListener('click', () => {
                document.querySelectorAll('.lapangan-card').forEach(c => {
                    c.classList.remove('border-amber-400');
                    c.classList.add('border-white/10');
                    c.querySelector('.lapangan-check').classList.add('hidden');
                    c.querySelector('.lapangan-check').classList.remove('flex');
                });
                card.classList.remove('border-white/10');
                card.classList.add('border-amber-400');
                card.querySelector('.lapangan-check').classList.remove('hidden');
                card.querySelector('.lapangan-check').classList.add('flex');

                const select = document.getElementById('lapanganSelect');
                select.value = card.dataset.id;
                select.dispatchEvent(new Event('change'));
            });
        });

        function isWeekendOrHoliday(dateStr) {
            if (!dateStr) return false;
            const d   = new Date(dateStr + 'T00:00:00');
            const day = d.getDay(); // 0 = Minggu, 6 = Sabtu
            if (day === 0 || day === 6) return true;
            return HOLIDAYS.includes(dateStr);
        }

        function cariTarif(kategori, tipeHari, jamMulaiStr) {
            return TARIFS.find(t =>
                t.kategori    === kategori &&
                t.tipe_hari   === tipeHari &&
                jamMulaiStr   >= t.jam_mulai.substring(0, 5) &&
                jamMulaiStr   <  t.jam_selesai.substring(0, 5)
            );
        }

        function hitungJamSelesaiDanHarga() {
            const lapanganSelect = document.getElementById('lapanganSelect');
            const tanggalMain    = document.getElementById('tanggal_main').value;
            const jamMulai       = document.getElementById('jamMulai').value;
            const durasi         = parseInt(document.getElementById('durasiJam').value || 0);

            const selesaiEl  = document.getElementById('jamSelesaiDisplay');
            const totalEl    = document.getElementById('totalHarga');
            const tipeHariEl = document.getElementById('tipeHariDisplay');
            const dpEl       = document.getElementById('dpHarga');

            const opt      = lapanganSelect.options[lapanganSelect.selectedIndex];
            const kategori = opt ? opt.dataset.kategori : null;

            if (!jamMulai || !durasi) {
                selesaiEl.textContent  = '—';
                totalEl.textContent    = 'Rp 0';
                dpEl.textContent       = 'Rp 0';
                tipeHariEl.textContent = '—';
                return;
            }

            const [h, m]    = jamMulai.split(':').map(Number);
            let totalMenit  = (h * 60 + m) + (durasi * 60);

            if (totalMenit > 21 * 60) {
                selesaiEl.textContent = 'Melewati jam operasional (tutup 21:00)!';
                selesaiEl.classList.add('text-red-400');
                totalEl.textContent = 'Rp 0';
                dpEl.textContent    = 'Rp 0';
                return;
            }
            selesaiEl.classList.remove('text-red-400');

            const jamSelesai    = Math.floor(totalMenit / 60);
            const menitSelesai  = totalMenit % 60;
            selesaiEl.textContent = String(jamSelesai).padStart(2, '0') + ':' + String(menitSelesai).padStart(2, '0');

            const weekend          = isWeekendOrHoliday(tanggalMain);
            tipeHariEl.textContent = weekend ? 'Weekend / Tanggal Merah' : 'Weekday';

            if (!kategori || !tanggalMain) {
                totalEl.textContent = 'Rp 0';
                dpEl.textContent    = 'Rp 0';
                return;
            }

            const tarif = cariTarif(kategori, weekend ? 'weekend' : 'weekday', jamMulai);

            if (!tarif) {
                totalEl.textContent = 'Tarif tidak ditemukan';
                dpEl.textContent    = 'Rp 0';
                return;
            }

            const total = durasi * tarif.harga;
            const dp    = total / 2;
            totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
            dpEl.textContent    = 'Rp ' + dp.toLocaleString('id-ID');
        }

        function cekPenutupan() {
            const lapanganId = document.getElementById('lapanganSelect').value;
            const tanggal    = document.getElementById('tanggal_main').value;
            const warningEl  = document.getElementById('closureWarning');
            const messageEl  = document.getElementById('closureMessage');
            const periodEl   = document.getElementById('closurePeriod');
            const submitBtn  = document.getElementById('submitBtn');

            if (!lapanganId || !tanggal) {
                warningEl.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                return;
            }

            const closure = PENUTUPANS.find(p =>
                p.lapangan_id == lapanganId &&
                tanggal >= p.tanggal_mulai &&
                tanggal <= p.tanggal_selesai
            );

            if (closure) {
                messageEl.textContent = closure.keterangan
                    ? `Alasan: ${closure.keterangan}`
                    : 'Lapangan sedang ditutup pada tanggal ini.';
                periodEl.textContent = `Periode tutup: ${closure.tanggal_mulai} s/d ${closure.tanggal_selesai}`;
                warningEl.classList.remove('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                warningEl.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        document.getElementById('lapanganSelect').addEventListener('change', () => { hitungJamSelesaiDanHarga(); cekPenutupan(); });
        document.getElementById('tanggal_main').addEventListener('change', () => { hitungJamSelesaiDanHarga(); cekPenutupan(); });
        document.getElementById('jamMulai').addEventListener('change', hitungJamSelesaiDanHarga);
        document.getElementById('durasiJam').addEventListener('change', hitungJamSelesaiDanHarga);

        window.addEventListener('DOMContentLoaded', () => {
            hitungJamSelesaiDanHarga();
            cekPenutupan();
        });
    </script>
    <style>
        /* Pertahankan warna kontrol; cukup cerahkan ikon tanggal dan panah durasi. */
        #bookingForm input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            filter: invert(1);
            opacity: 1;
        }

        #bookingForm select:not(.hidden) {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23ffffff' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='m6 9 6 6 6-6'/%3E%3C/svg%3E") !important;
            background-position: right 1rem center !important;
            background-repeat: no-repeat !important;
            background-size: 1.25rem !important;
            padding-right: 3rem !important;
        }
    </style>
</x-app-layout>
