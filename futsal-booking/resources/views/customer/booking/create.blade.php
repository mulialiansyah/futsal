<x-app-layout>

    <div class="max-w-5xl mx-auto py-6" x-data="bookingApp()">
        <div class="overflow-hidden rounded-2xl border border-[#23282A] bg-[#14181A] shadow-2xl shadow-black/20">
            <!-- ===== HEADER ===== -->
            <div class="border-b border-[#23282A] px-5 pt-5 pb-4">
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-emerald-400">Pemesanan Lapangan</p>
                <h1 class="mt-1 font-display text-2xl text-white">Jadwalkan Pertandinganmu</h1>
                <p class="mt-1 text-sm text-neutral-400">Pilih tanggal, kategori, lapangan, dan slot jam ketersediaan di bawah ini.</p>
            </div>

            <!-- ===== VALIDATION ERRORS ===== -->
            @if ($errors->any())
                <div class="m-5 p-4 bg-red-500/10 border border-red-500/30 text-red-300 rounded-xl text-sm">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="px-5 py-5 space-y-6">

                <!-- 1. DATE PICKER (BARIS ATAS) -->
                <div>
                    <label class="block text-xs font-semibold text-neutral-400 uppercase tracking-wider mb-3">📅 Pilih Tanggal Main</label>
                    <div class="flex overflow-x-auto gap-3 pb-3 scrollbar-thin scrollbar-thumb-emerald-600 scrollbar-track-[#14181A]">
                        @foreach($dates as $d)
                            <button type="button"
                                    @click="setDate('{{ $d['value'] }}')"
                                    :class="selectedDate === '{{ $d['value'] }}' ? 'border-emerald-500 bg-emerald-600 text-white' : 'border-[#23282A] bg-[#0B0F0C] text-neutral-400 hover:border-neutral-500'"
                                    class="flex-shrink-0 flex flex-col items-center justify-center w-24 py-3 rounded-xl border transition duration-150">
                                <span class="text-[10px] uppercase font-bold tracking-wider opacity-75">
                                    {{ $d['is_today'] ? 'Hari Ini' : substr($d['day_name'], 0, 3) }}
                                </span>
                                <span class="text-xl font-extrabold my-0.5">{{ $d['day'] }}</span>
                                <span class="text-[10px] font-medium tracking-wide uppercase">{{ $d['month'] }}</span>
                                <span class="text-[9px] mt-1 font-semibold text-amber-400 font-mono">{{ $d['price_range'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- 2. PEMILIHAN KATEGORI LAPANGAN -->
                <div>
                    <label class="block text-xs font-semibold text-neutral-400 uppercase tracking-wider mb-3">🏟️ Pilih Kategori Lapangan</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Standar -->
                        <button type="button"
                                @click="setKategoriFilter('standar')"
                                :class="selectedKategoriFilter === 'standar' ? 'border-emerald-500 bg-[#16321F] text-white' : 'border-[#23282A] bg-[#0B0F0C] text-neutral-400 hover:border-neutral-500'"
                                class="text-left p-4 rounded-xl border transition duration-150 flex justify-between items-center">
                            <div>
                                <div class="font-bold text-sm text-white">Standar</div>
                                <div class="text-xs text-neutral-500 mt-1">Lapangan futsal standar dengan permukaan sintetis & vinyl</div>
                            </div>
                            <div class="w-5 h-5 rounded-full border flex items-center justify-center"
                                 :class="selectedKategoriFilter === 'standar' ? 'border-emerald-500 bg-emerald-500' : 'border-neutral-600'">
                                <svg x-show="selectedKategoriFilter === 'standar'" class="w-3 h-3 text-neutral-950" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </button>
                        <!-- Internasional -->
                        <button type="button"
                                @click="setKategoriFilter('internasional')"
                                :class="selectedKategoriFilter === 'internasional' ? 'border-emerald-500 bg-[#16321F] text-white' : 'border-[#23282A] bg-[#0B0F0C] text-neutral-400 hover:border-neutral-500'"
                                class="text-left p-4 rounded-xl border transition duration-150 flex justify-between items-center">
                            <div>
                                <div class="font-bold text-sm text-white">Internasional</div>
                                <div class="text-xs text-neutral-500 mt-1">Lapangan futsal berstandar internasional, kualitas premium</div>
                            </div>
                            <div class="w-5 h-5 rounded-full border flex items-center justify-center"
                                 :class="selectedKategoriFilter === 'internasional' ? 'border-emerald-500 bg-emerald-500' : 'border-neutral-600'">
                                <svg x-show="selectedKategoriFilter === 'internasional'" class="w-3 h-3 text-neutral-950" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- 3. PEMILIHAN LAPANGAN -->
                <div>
                    <label class="block text-xs font-semibold text-neutral-400 uppercase tracking-wider mb-3">📍 Pilih Lapangan</label>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                        @foreach($lapangans as $lapangan)
                            <button type="button"
                                    x-show="shouldShowLapangan('{{ $lapangan->kategori }}')"
                                    @click="setLapangan({{ $lapangan->id }}, '{{ $lapangan->kategori }}')"
                                    :class="selectedLapanganId === {{ $lapangan->id }} ? 'border-emerald-500 bg-[#16321F]' : 'border-[#23282A] bg-[#0B0F0C] hover:border-neutral-500'"
                                    class="text-left rounded-xl overflow-hidden border transition duration-150 relative">
                                <div class="relative h-24 sm:h-28">
                                    <img src="{{ $lapangan->foto_utama ? $lapangan->foto_utama->url : 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?q=80&w=800&auto=format&fit=crop' }}"
                                         alt="{{ $lapangan->nama_lapangan }}"
                                         class="w-full h-full object-cover">
                                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded-full bg-black/60 backdrop-blur-sm text-[10px] font-bold text-white">
                                        {{ $lapangan->kategori_label }}
                                    </span>
                                    <div class="absolute top-2 right-2 w-5 h-5 rounded-full bg-emerald-500 items-center justify-center"
                                         :class="selectedLapanganId === {{ $lapangan->id }} ? 'flex' : 'hidden'">
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
                </div>

                <!-- 4. GRID TIME SLOT -->
                <div x-show="selectedLapanganId !== null" x-transition>
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-3 gap-2">
                        <label class="block text-xs font-semibold text-neutral-400 uppercase tracking-wider">🕒 Pilih Jam Mulai</label>
                        <!-- Legend -->
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px] text-neutral-400">
                            <span class="flex items-center gap-1.5">
                                <span class="inline-block w-3.5 h-3.5 rounded-sm bg-emerald-950/60 border border-emerald-700/50"></span>
                                Tersedia
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="inline-block w-3.5 h-3.5 rounded-sm bg-neutral-950 border border-neutral-800"></span>
                                <span class="text-neutral-500">Waktu Lewat</span>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="inline-block w-3.5 h-3.5 rounded-sm bg-rose-950/60 border border-rose-800/60"></span>
                                <span class="text-rose-400/80">Dibooking</span>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="inline-block w-3.5 h-3.5 rounded-sm bg-amber-950/60 border border-amber-700/50"></span>
                                <span class="text-amber-400/80">Lap. Tutup</span>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="inline-block w-3.5 h-3.5 rounded-sm bg-emerald-400 border border-emerald-300"></span>
                                Terpilih
                            </span>
                        </div>
                    </div>

                    <!-- Loader & State Messages -->
                    <div x-show="loadingSlots" class="py-12 flex flex-col items-center justify-center gap-3">
                        <div class="w-8 h-8 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
                        <span class="text-xs text-neutral-500 font-medium">Memuat jadwal lapangan...</span>
                    </div>

                    <!-- Cinema-style Slot Grid -->
                    <div x-show="!loadingSlots && slots.length > 0">
                        <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10 gap-2">
                            <template x-for="slot in slots" :key="slot.jam_mulai">
                                <button type="button"
                                        @click="slot.status === 'available' && selectStartSlot(slot)"
                                        :disabled="slot.status !== 'available'"
                                        :class="getSlotClass(slot)"
                                        class="relative py-2.5 px-1 rounded-lg text-center border font-bold transition-all duration-100 text-xs flex flex-col items-center justify-center gap-0.5 select-none">
                                    <span x-text="slot.jam_mulai" class="text-sm font-mono tracking-tight leading-none"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div x-show="!loadingSlots && slots.length === 0" class="p-8 text-center bg-[#0B0F0C] border border-[#23282A] rounded-xl text-neutral-500 text-sm">
                        Tidak ada slot operasional untuk lapangan ini.
                    </div>
                </div>

                <!-- 5. DURATION SELECTION (TAMPIL SETELAH JAM MULAI DIKLIK) -->
                <div x-show="selectedStartSlot !== null" x-transition class="p-5 bg-[#0B0F0C] border border-[#23282A] rounded-2xl space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <h4 class="font-bold text-white text-base">⏱ Pilih Durasi Pertandingan</h4>
                            <p class="text-xs text-neutral-400 mt-0.5">Jam Mulai: <span class="font-bold text-emerald-400" x-text="selectedStartSlot"></span></p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="adjustDuration(-1)" class="w-10 h-10 rounded-lg bg-[#14181A] border border-[#23282A] hover:border-neutral-500 font-extrabold text-white text-lg flex items-center justify-center transition">-</button>
                            <span class="text-xl font-extrabold text-white w-12 text-center"><span x-text="selectedDuration"></span> Jam</span>
                            <button type="button" @click="adjustDuration(1)" class="w-10 h-10 rounded-lg bg-[#14181A] border border-[#23282A] hover:border-neutral-500 font-extrabold text-white text-lg flex items-center justify-center transition">+</button>
                        </div>
                    </div>

                    <!-- Highlight Info / Warning -->
                    <div x-show="errorMessage" class="p-3 bg-red-500/10 border border-red-500/30 text-red-400 text-xs rounded-xl flex items-center gap-2">
                        <span>⚠️</span>
                        <span x-text="errorMessage"></span>
                    </div>

                    <div x-show="!errorMessage" class="p-3 bg-emerald-500/5 border border-emerald-500/10 text-emerald-400 text-xs rounded-xl flex items-center justify-between">
                        <span class="flex items-center gap-2">
                            <span>⚽</span>
                            <span>Slot Terpilih: <strong x-text="getSelectedSlotsRange()"></strong></span>
                        </span>
                        <span class="font-mono text-neutral-400 font-medium">Selesai: <span x-text="getEndTime()"></span></span>
                    </div>
                </div>

                <!-- 6. PRICING SUMMARY -->
                <div x-show="selectedStartSlot !== null && !errorMessage" x-transition class="bg-[#0B0F0C] border border-[#23282A] rounded-xl p-4">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-neutral-400">Tipe Hari:</span>
                        <span class="text-sm font-semibold text-white" x-text="isWeekend ? 'Weekend / Tanggal Merah' : 'Weekday'"></span>
                    </div>
                    <div class="flex justify-between items-center mb-3 border-t border-white/10 pt-3">
                        <span class="text-sm text-neutral-400">Total Harga:</span>
                        <span class="text-lg font-extrabold text-white" x-text="formatRupiah(totalHarga)"></span>
                    </div>
                    <div class="flex justify-between items-center bg-amber-400/10 border border-amber-400/20 p-3 rounded-xl">
                        <span class="text-xs text-amber-300 font-bold">Bisa DP Dulu (Min 50%):</span>
                        <span class="text-sm font-extrabold text-amber-300" x-text="formatRupiah(dpHarga)"></span>
                    </div>
                    <p class="text-neutral-500 text-xs mt-3">* Harga final diverifikasi server. Batas waktu pelunasan & pembayaran online 10 menit setelah booking dibuat.</p>
                </div>

                <!-- 7. METODE PEMBAYARAN -->
                <div x-show="selectedStartSlot !== null && !errorMessage" x-transition>
                    <p class="mb-3 text-xs font-semibold text-neutral-400 uppercase tracking-wider">💳 Pilih Metode Pembayaran</p>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <label @click="metodePembayaran = 'midtrans'"
                               :class="metodePembayaran === 'midtrans' ? 'border-emerald-500 bg-[#16321F]' : 'border-[#23282A] bg-[#0B0F0C] hover:border-neutral-500'"
                               class="cursor-pointer rounded-xl border p-4 transition flex items-center justify-between">
                            <div>
                                <span class="block font-bold text-white">💳 Bayar Online (Midtrans)</span>
                                <span class="mt-1 block text-xs text-neutral-400">Bayar instan via Transfer Bank, QRIS, e-Wallet. Slot dikunci 10 menit.</span>
                            </div>
                            <div class="w-4 h-4 rounded-full border flex items-center justify-center"
                                 :class="metodePembayaran === 'midtrans' ? 'border-emerald-500 bg-emerald-500' : 'border-neutral-600'">
                                <div class="w-1.5 h-1.5 rounded-full bg-neutral-950" x-show="metodePembayaran === 'midtrans'"></div>
                            </div>
                        </label>
                        <label @click="metodePembayaran = 'cash'"
                               :class="metodePembayaran === 'cash' ? 'border-amber-400 bg-amber-500/10' : 'border-[#23282A] bg-[#0B0F0C] hover:border-neutral-500'"
                               class="cursor-pointer rounded-xl border p-4 transition flex items-center justify-between">
                            <div>
                                <span class="block font-bold text-amber-300">💵 Bayar di Lokasi (Cash)</span>
                                <span class="mt-1 block text-xs text-neutral-400">Konfirmasi pemesanan, bayar tunai di lokasi kepada admin saat bermain.</span>
                            </div>
                            <div class="w-4 h-4 rounded-full border flex items-center justify-center"
                                 :class="metodePembayaran === 'cash' ? 'border-amber-400 bg-amber-400' : 'border-neutral-600'">
                                <div class="w-1.5 h-1.5 rounded-full bg-neutral-950" x-show="metodePembayaran === 'cash'"></div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- SUBMIT FORM -->
                <form action="{{ route('customer.booking.store') }}" method="POST" id="bookingForm" x-show="selectedStartSlot !== null && !errorMessage" x-transition class="pt-2">
                    @csrf
                    <input type="hidden" name="lapangan_id" :value="selectedLapanganId">
                    <input type="hidden" name="tanggal_main" :value="selectedDate">
                    <input type="hidden" name="jam_mulai" :value="selectedStartSlot">
                    <input type="hidden" name="durasi_jam" :value="selectedDuration">
                    <input type="hidden" name="metode_pembayaran" :value="metodePembayaran">

                    <button type="submit"
                            :class="metodePembayaran === 'cash' ? 'bg-amber-500 hover:bg-amber-400 shadow-amber-950/40' : 'bg-emerald-600 hover:bg-emerald-500 shadow-emerald-950/40'"
                            class="group w-full text-neutral-950 font-extrabold px-8 py-4 rounded-full shadow-lg transition duration-200 text-center text-sm flex items-center justify-center gap-2">
                        <span x-text="metodePembayaran === 'cash' ? 'Konfirmasi Booking Cash ✓' : 'Lanjut ke Pembayaran Online ›'"></span>
                    </button>
                </form>

            </div>
        </div>
    </div>

    <!-- FLOATING CHAT KE ADMIN -->
    <a href="https://wa.me/62895610031040?text={{ urlencode('Halo min, saya mau tanya soal booking lapangan.') }}"
       target="_blank" rel="noopener"
       class="fixed right-5 bottom-24 z-40 w-14 h-14 rounded-full bg-emerald-500 hover:bg-emerald-600 shadow-lg shadow-emerald-500/30 flex items-center justify-center transition"
       title="Chat Admin via WhatsApp">
        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.48 1.32 5 L2 22l5.25-1.38a9.9 9.9 0 004.79 1.22h.01c5.46 0 9.9-4.45 9.9-9.9 0-2.65-1.03-5.14-2.9-7.01A9.87 9.87 0 0012.04 2zm5.8 14.16c-.24.68-1.4 1.32-1.93 1.4-.5.08-1.12.11-1.8-.11-.42-.13-.95-.31-1.64-.6-2.88-1.24-4.76-4.14-4.9-4.33-.14-.19-1.17-1.56-1.17-2.98 0-1.42.74-2.11 1-2.4.26-.29.57-.36.76-.36.19 0 .38 0 .55.01.18.01.41-.07.64.49.24.58.81 2 .88 2.15.07.15.12.32.02.51-.1.19-.15.31-.3.48-.14.17-.3.37-.43.5-.14.14-.29.29-.13.57.17.29.75 1.24 1.62 2.01 1.11.99 2.05 1.3 2.34 1.44.29.14.46.12.63-.07.17-.19.72-.84.91-1.13.19-.29.38-.24.64-.15.26.1 1.66.79 1.94.93.29.14.48.22.55.34.07.13.07.71-.17 1.39z"/>
        </svg>
    </a>

    <script>
        const TARIFS   = @json($tarifs);
        const HOLIDAYS = @json($holidays);

        function bookingApp() {
            return {
                selectedDate: '{{ $dates[0]['value'] }}',
                selectedKategoriFilter: 'standar',
                selectedLapanganId: @json(request('lapangan_id') ? (int)request('lapangan_id') : null),
                selectedKategori: '',
                slots: [],
                loadingSlots: false,
                selectedStartSlot: null,
                selectedDuration: 1,   // in HOURS (integer)
                metodePembayaran: 'midtrans',
                totalHarga: 0,
                dpHarga: 0,
                errorMessage: '',
                isWeekend: false,

                init() {
                    this.checkWeekend();

                    if (this.selectedLapanganId) {
                        this.fetchSlots();
                    }
                },

                setDate(date) {
                    this.selectedDate = date;
                    this.checkWeekend();
                    this.resetSlotSelection();
                    if (this.selectedLapanganId) {
                        this.fetchSlots();
                    }
                },

                setKategoriFilter(kategori) {
                    this.selectedKategoriFilter = kategori;
                    this.selectedLapanganId = null;
                    this.selectedKategori = '';
                    this.resetSlotSelection();
                },

                setLapangan(lapanganId, kategori) {
                    this.selectedLapanganId = lapanganId;
                    this.selectedKategori = kategori;
                    this.resetSlotSelection();
                    this.fetchSlots();
                },

                shouldShowLapangan(kategori) {
                    return kategori === this.selectedKategoriFilter;
                },

                checkWeekend() {
                    if (!this.selectedDate) return;
                    const d = new Date(this.selectedDate + 'T00:00:00');
                    const day = d.getDay();
                    const isSatSun = (day === 0 || day === 6);
                    this.isWeekend = isSatSun || HOLIDAYS.includes(this.selectedDate);
                },

                resetSlotSelection() {
                    this.selectedStartSlot = null;
                    this.selectedDuration = 1;
                    this.errorMessage = '';
                    this.totalHarga = 0;
                    this.dpHarga = 0;
                },

                fetchSlots() {
                    if (!this.selectedLapanganId || !this.selectedDate) return;
                    this.loadingSlots = true;

                    fetch(`{{ route('customer.booking.check-slots') }}?lapangan_id=${this.selectedLapanganId}&tanggal=${this.selectedDate}`)
                        .then(res => res.json())
                        .then(data => {
                            this.slots = data.slots;
                            this.loadingSlots = false;
                        })
                        .catch(err => {
                            console.error(err);
                            this.loadingSlots = false;
                        });
                },

                selectStartSlot(slot) {
                    if (slot.status !== 'available') return;
                    this.selectedStartSlot = slot.jam_mulai;
                    this.selectedDuration = 1;
                    this.validateAndCalculate();
                },

                // Duration is in HOURS; each hour covers 2 x 30-min slots
                adjustDuration(val) {
                    const newDur = this.selectedDuration + val;
                    if (newDur < 1) return;

                    const startIdx = this.slots.findIndex(s => s.jam_mulai === this.selectedStartSlot);
                    if (startIdx === -1) return;

                    // Each hour = 2 slots of 30 min
                    const slotsNeeded = newDur * 2;
                    if (startIdx + slotsNeeded > this.slots.length) return;

                    this.selectedDuration = newDur;
                    this.validateAndCalculate();
                },

                validateAndCalculate() {
                    this.errorMessage = '';
                    if (!this.selectedStartSlot) return;

                    const startIdx = this.slots.findIndex(s => s.jam_mulai === this.selectedStartSlot);
                    if (startIdx === -1) return;

                    let totalHargaAccum = 0;
                    const tipeHariKey = this.isWeekend ? 'weekend' : 'weekday';

                    // Check each 30-min sub-slot within the duration
                    const slotsPerHour = 2;
                    const totalSubSlots = this.selectedDuration * slotsPerHour;

                    for (let i = 0; i < totalSubSlots; i++) {
                        const currentSlot = this.slots[startIdx + i];
                        if (!currentSlot) {
                            this.errorMessage = 'Durasi melewati jam operasional venue.';
                            return;
                        }
                        // Skip start slot itself when checking availability
                        if (i > 0 && currentSlot.status !== 'available') {
                            this.errorMessage = `Slot jam ${currentSlot.jam_mulai} sudah dipesan atau tidak tersedia. Pilih durasi lebih pendek atau jam mulai lain.`;
                            return;
                        }

                        // Only accumulate price per full-hour slot (every 2 sub-slots = 1 hour)
                        if (i % slotsPerHour === 0) {
                            const tarif = TARIFS.find(t =>
                                t.kategori === this.selectedKategori &&
                                t.tipe_hari === tipeHariKey &&
                                currentSlot.jam_mulai >= t.jam_mulai.substring(0, 5) &&
                                currentSlot.jam_mulai < t.jam_selesai.substring(0, 5)
                            );
                            totalHargaAccum += tarif ? parseInt(tarif.harga) : 100000;
                        }
                    }

                    this.totalHarga = totalHargaAccum;
                    this.dpHarga = totalHargaAccum / 2;
                },

                // Slot is part of selected range if within [startIdx, startIdx + duration*2)
                isSlotInRange(slot) {
                    if (!this.selectedStartSlot) return false;
                    const startIdx = this.slots.findIndex(s => s.jam_mulai === this.selectedStartSlot);
                    const currentIdx = this.slots.findIndex(s => s.jam_mulai === slot.jam_mulai);
                    if (startIdx === -1 || currentIdx === -1) return false;
                    return currentIdx >= startIdx && currentIdx < startIdx + (this.selectedDuration * 2);
                },

                getSlotClass(slot) {
                    if (slot.status === 'past') {
                        return 'border-neutral-800 bg-neutral-950/80 text-neutral-600 cursor-not-allowed line-through decoration-dashed decoration-neutral-700/50';
                    }
                    if (slot.status === 'booked') {
                        return 'border-rose-900/60 bg-rose-950/40 text-rose-400/70 cursor-not-allowed font-semibold';
                    }
                    if (slot.status === 'tutup') {
                        return 'border-amber-800/40 bg-amber-950/40 text-amber-400/70 cursor-not-allowed font-semibold';
                    }

                    if (this.isSlotInRange(slot)) {
                        if (slot.jam_mulai === this.selectedStartSlot) {
                            return 'border-emerald-400 bg-emerald-400 text-neutral-950 shadow-md shadow-emerald-500/30 scale-105';
                        }
                        return 'border-emerald-600 bg-emerald-700 text-white shadow-sm shadow-emerald-800/30';
                    }

                    return 'border-emerald-700/40 bg-emerald-950/30 text-emerald-300 hover:border-emerald-500 hover:bg-emerald-900/50 hover:scale-105 cursor-pointer';
                },

                getEndTime() {
                    if (!this.selectedStartSlot) return '';
                    const [h, m] = this.selectedStartSlot.split(':').map(Number);
                    const totalMin = (h * 60 + m) + (this.selectedDuration * 60);
                    const fh = Math.floor(totalMin / 60);
                    const fm = totalMin % 60;
                    return String(fh).padStart(2, '0') + ':' + String(fm).padStart(2, '0');
                },

                getSelectedSlotsRange() {
                    if (!this.selectedStartSlot) return '';
                    return `${this.selectedStartSlot} - ${this.getEndTime()}`;
                },

                formatRupiah(num) {
                    return 'Rp ' + num.toLocaleString('id-ID');
                }
            }
        }
    </script>
</x-app-layout>
