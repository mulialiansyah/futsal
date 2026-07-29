<x-app-layout>
    <div class="max-w-5xl mx-auto py-6" x-data="rescheduleApp()">
        <div class="overflow-hidden rounded-2xl border border-[#23282A] bg-[#14181A] shadow-2xl shadow-black/20">
            <!-- ===== HEADER ===== -->
            <div class="border-b border-[#23282A] px-5 pt-5 pb-4 bg-amber-500/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-amber-400">Pindahkan Jadwal Booking</p>
                        <h1 class="mt-1 font-display text-2xl text-white">Pilih Lapangan & Slot Pengganti (#{{ $booking->id }})</h1>
                        <p class="mt-1 text-sm text-neutral-300">Pilih tanggal, lapangan, dan slot jam baru tanpa perlu membayar ulang dari awal.</p>
                    </div>
                    <a href="{{ route('customer.booking.show', $booking) }}" class="px-4 py-2 bg-neutral-800 hover:bg-neutral-700 text-neutral-300 text-xs font-semibold rounded-xl transition border border-neutral-700">
                        &larr; Batal & Kembali
                    </a>
                </div>
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

                <!-- 1. DATE PICKER -->
                <div>
                    <label class="block text-xs font-semibold text-neutral-400 uppercase tracking-wider mb-3">📅 Pilih Tanggal Main Baru</label>
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
                    <label class="block text-xs font-semibold text-neutral-400 uppercase tracking-wider mb-3">📍 Pilih Lapangan Pengganti</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($lapangans as $lap)
                            <button type="button"
                                    x-show="shouldShowLapangan('{{ $lap->kategori }}')"
                                    @click="setLapangan({{ $lap->id }}, '{{ $lap->kategori }}')"
                                    :class="selectedLapanganId === {{ $lap->id }} ? 'border-emerald-500 bg-[#16321F] text-white' : 'border-[#23282A] bg-[#0B0F0C] text-neutral-400 hover:border-neutral-500'"
                                    class="text-left p-4 rounded-xl border transition duration-150 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-sm text-white">{{ $lap->nama_lapangan }}</span>
                                        <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-neutral-800 text-neutral-300">
                                            {{ $lap->kategori }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-neutral-500 mt-1 line-clamp-2">{{ $lap->deskripsi ?? 'Lapangan futsal standar berkualitas.' }}</p>
                                </div>
                                <div class="mt-3 pt-3 border-t border-[#23282A] flex justify-between items-center text-xs">
                                    <span class="text-neutral-400 font-mono">Rp {{ number_format($lap->harga_per_jam ?? 100000, 0, ',', '.') }}/jam</span>
                                    <div class="w-4 h-4 rounded-full border flex items-center justify-center"
                                         :class="selectedLapanganId === {{ $lap->id }} ? 'border-emerald-500 bg-emerald-500' : 'border-neutral-600'">
                                        <svg x-show="selectedLapanganId === {{ $lap->id }}" class="w-2.5 h-2.5 text-neutral-950" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- 3. DURASI MAIN -->
                <div>
                    <label class="block text-xs font-semibold text-neutral-400 uppercase tracking-wider mb-2">⏱️ Durasi Main (Jam)</label>
                    <div class="flex items-center gap-3">
                        <button type="button" @click="adjustDuration(-1)" class="w-10 h-10 rounded-xl bg-[#0B0F0C] border border-[#23282A] text-white font-bold hover:bg-neutral-800 transition">-</button>
                        <span class="text-xl font-bold text-white w-16 text-center" x-text="durasi + ' Jam'"></span>
                        <button type="button" @click="adjustDuration(1)" class="w-10 h-10 rounded-xl bg-[#0B0F0C] border border-[#23282A] text-white font-bold hover:bg-neutral-800 transition">+</button>
                    </div>
                </div>

                <!-- 4. CINEMA SLOT GRID -->
                <div>
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-3 gap-2">
                        <label class="block text-xs font-semibold text-neutral-400 uppercase tracking-wider">🕒 Pilih Jam Mulai Baru</label>
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
                                <span class="inline-block w-3.5 h-3.5 rounded-sm bg-[#1f1010] border border-red-900/50"></span>
                                Harga Lebih Murah
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="inline-block w-3.5 h-3.5 rounded-sm bg-emerald-400 border border-emerald-300"></span>
                                Terpilih
                            </span>
                        </div>
                    </div>

                    <div x-show="loadingSlots" class="py-8 text-center text-neutral-400 text-xs">
                        <div class="w-8 h-8 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin mx-auto mb-2"></div>
                        Mengambil data ketersediaan slot...
                    </div>

                    <!-- Cinema-style Slot Grid -->
                    <div x-show="!loadingSlots" class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10 gap-2">
                        <template x-for="slot in slots" :key="slot.jam_mulai">
                            <button type="button"
                                    @click="slot.status === 'available' && !isPriceTooLow(slot) && selectSlot(slot)"
                                    :disabled="slot.status !== 'available' || isPriceTooLow(slot)"
                                    :class="getSlotClass(slot)"
                                    :title="isPriceTooLow(slot) ? 'Harga lebih rendah dari booking asli, tidak dapat dipilih' : ''"
                                    class="relative py-2.5 px-1 rounded-lg text-center border font-bold transition-all duration-100 text-xs flex flex-col items-center justify-center select-none">
                                <span class="font-mono text-sm tracking-tight leading-none" x-text="slot.jam_mulai"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- 5. RANGE INFO -->
                <div x-show="selectedJamMulai" x-transition class="p-3 bg-emerald-500/5 border border-emerald-500/10 text-emerald-400 text-xs rounded-xl flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <span>⚽</span>
                        <span>Slot Terpilih: <strong x-text="selectedJamMulai + ' — ' + getEndTime()"></strong></span>
                    </span>
                    <span class="font-mono text-neutral-400">Selesai: <span x-text="getEndTime()"></span></span>
                </div>

                <!-- Price Comparison Card -->
                <div x-show="selectedJamMulai" x-transition class="bg-[#0B0F0C] border border-[#23282A] rounded-2xl p-5 space-y-3">
                    <h4 class="font-bold text-white text-sm">💰 Perbandingan Harga Reschedule</h4>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div class="p-3 bg-neutral-900/50 rounded-xl border border-white/5">
                            <div class="text-[10px] uppercase font-bold tracking-wider text-neutral-400">Harga Lama</div>
                            <div class="text-base font-extrabold text-neutral-300 mt-1">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</div>
                        </div>
                        <div class="p-3 bg-neutral-900/50 rounded-xl border border-white/5">
                            <div class="text-[10px] uppercase font-bold tracking-wider text-neutral-400">Harga Baru</div>
                            <div class="text-base font-extrabold text-emerald-400 mt-1" x-text="'Rp ' + calculatedNewPrice.toLocaleString('id-ID')"></div>
                        </div>
                        <div class="p-3 bg-neutral-900/50 rounded-xl border border-white/5">
                            <div class="text-[10px] uppercase font-bold tracking-wider text-neutral-400">Selisih</div>
                            <div class="text-base font-extrabold mt-1" :class="calculatedDiff > 0 ? 'text-amber-400' : 'text-emerald-500'" 
                                 x-text="calculatedDiff === 0 ? 'Rp 0' : '+ Rp ' + calculatedDiff.toLocaleString('id-ID')">
                            </div>
                        </div>
                    </div>
                    <div x-show="calculatedDiff > 0" class="text-xs text-amber-300 bg-amber-500/10 border border-amber-500/20 p-3 rounded-xl">
                        ⚠️ **Harga baru lebih mahal.** Selisih pembayaran sebesar <strong x-text="'Rp ' + calculatedDiff.toLocaleString('id-ID')"></strong> akan ditagihkan saat pelunasan booking.
                    </div>
                    <div x-show="calculatedDiff === 0" class="text-xs text-emerald-300 bg-emerald-500/10 border border-emerald-500/20 p-3 rounded-xl">
                        ✓ **Harga baru sama.** Tidak ada biaya tambahan yang diperlukan.
                    </div>
                </div>

                <!-- 6. FORM SUBMISSION -->
                <form method="POST" action="{{ route('customer.booking.process-reschedule', $booking) }}" class="mt-4 border-t border-[#23282A] pt-6">
                    @csrf
                    <input type="hidden" name="lapangan_id" :value="selectedLapanganId">
                    <input type="hidden" name="tanggal_main" :value="selectedDate">
                    <input type="hidden" name="jam_mulai" :value="selectedJamMulai">
                    <input type="hidden" name="durasi_jam" :value="durasi">

                    <div class="bg-[#0B0F0C] border border-[#23282A] p-5 rounded-2xl flex flex-col md:flex-row items-center justify-between gap-4">
                        <div>
                            <div class="text-xs text-neutral-400">Total Harga Sebelumnya: <strong class="text-neutral-200">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</strong></div>
                            <div class="text-lg font-extrabold text-white mt-1">
                                Jadwal Baru: <span class="text-emerald-400" x-text="selectedJamMulai ? (selectedDate + ' jam ' + selectedJamMulai) : 'Belum Dipilih'"></span>
                            </div>
                        </div>

                        <button type="submit"
                                :disabled="!selectedJamMulai"
                                :class="selectedJamMulai ? 'bg-emerald-600 hover:bg-emerald-500 text-white cursor-pointer shadow-lg shadow-emerald-900/30' : 'bg-neutral-800 text-neutral-500 cursor-not-allowed'"
                                class="w-full md:w-auto px-8 py-3.5 rounded-xl font-bold text-sm transition duration-200">
                            Konfirmasi Pindah Jadwal
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        const TARIFS = @json($tarifs);
        const HOLIDAYS = @json($holidays);
        const LAPANGANS = @json($lapangans);

        function rescheduleApp() {
            return {
                selectedDate: '{{ now()->toDateString() }}',
                selectedLapanganId: {{ $booking->lapangan_id }},
                selectedKategoriFilter: '{{ $booking->lapangan->kategori }}',
                durasi: {{ $booking->duration_hours ?? 1 }},   // in HOURS
                selectedJamMulai: null,
                slots: [],
                loadingSlots: false,
                originalHarga: {{ $booking->total_harga }},
                selectedKategori: '',
                isWeekend: false,
                calculatedNewPrice: 0,
                calculatedDiff: 0,

                init() {
                    this.updateKategori();
                    this.checkWeekend();
                    this.fetchSlots();
                },

                updateKategori() {
                    const lap = LAPANGANS.find(l => l.id === this.selectedLapanganId);
                    this.selectedKategori = lap ? lap.kategori : '';
                },

                shouldShowLapangan(kategori) {
                    return kategori === this.selectedKategoriFilter;
                },

                setKategoriFilter(kategori) {
                    this.selectedKategoriFilter = kategori;
                    this.selectedLapanganId = null;
                    this.selectedKategori = '';
                    this.selectedJamMulai = null;
                    this.updateCalculatedPrices();
                },

                checkWeekend() {
                    if (!this.selectedDate) return;
                    const d = new Date(this.selectedDate + 'T00:00:00');
                    const day = d.getDay();
                    const isSatSun = (day === 0 || day === 6);
                    this.isWeekend = isSatSun || HOLIDAYS.includes(this.selectedDate);
                },

                setDate(date) {
                    this.selectedDate = date;
                    this.selectedJamMulai = null;
                    this.checkWeekend();
                    this.updateCalculatedPrices();
                    this.fetchSlots();
                },

                setLapangan(id, kategori) {
                    this.selectedLapanganId = id;
                    this.selectedKategori = kategori || '';
                    this.updateKategori();
                    this.selectedJamMulai = null;
                    this.updateCalculatedPrices();
                    this.fetchSlots();
                },

                adjustDuration(val) {
                    const newDur = this.durasi + val;
                    if (newDur < 1) return;

                    if (this.selectedJamMulai) {
                        const startIdx = this.slots.findIndex(s => s.jam_mulai === this.selectedJamMulai);
                        const slotsNeeded = newDur * 2; // 2 slots per hour (30-min intervals)
                        if (startIdx !== -1 && startIdx + slotsNeeded > this.slots.length) return;
                    }

                    this.durasi = newDur;
                    // Re-validate selection range
                    if (this.selectedJamMulai) {
                        this.validateRange();
                    }
                    this.updateCalculatedPrices();
                },

                async fetchSlots() {
                    if (!this.selectedLapanganId || !this.selectedDate) return;
                    this.loadingSlots = true;
                    try {
                        const res = await fetch(`{{ route('customer.booking.check-slots') }}?lapangan_id=${this.selectedLapanganId}&tanggal=${this.selectedDate}`);
                        const data = await res.json();
                        this.slots = data.slots || [];
                    } catch (e) {
                        console.error(e);
                    } finally {
                        this.loadingSlots = false;
                    }
                },

                // Validate that startSlot + durasi*2 slots are all available
                validateRange() {
                    if (!this.selectedJamMulai) return;
                    const startIdx = this.slots.findIndex(s => s.jam_mulai === this.selectedJamMulai);
                    if (startIdx === -1) { this.selectedJamMulai = null; return; }
                    const slotsNeeded = this.durasi * 2;
                    for (let i = 1; i < slotsNeeded; i++) {
                        const s = this.slots[startIdx + i];
                        if (!s || s.status !== 'available') { this.selectedJamMulai = null; return; }
                    }
                },

                // Check if the slot can cover the duration length
                isCoverable(slot) {
                    const startIdx = this.slots.findIndex(s => s.jam_mulai === slot.jam_mulai);
                    if (startIdx === -1) return false;
                    const slotsNeeded = this.durasi * 2;
                    if (startIdx + slotsNeeded > this.slots.length) return false;
                    return true;
                },

                calculatePriceForSlot(slot, dur) {
                    if (!slot) return 0;
                    const startIdx = this.slots.findIndex(s => s.jam_mulai === slot.jam_mulai);
                    if (startIdx === -1) return 0;

                    let totalHargaAccum = 0;
                    const tipeHariKey = this.isWeekend ? 'weekend' : 'weekday';
                    const slotsPerHour = 2;
                    const totalSubSlots = dur * slotsPerHour;

                    for (let i = 0; i < totalSubSlots; i++) {
                        const currentSlot = this.slots[startIdx + i];
                        if (!currentSlot) return 0;
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
                    return totalHargaAccum;
                },

                isPriceTooLow(slot) {
                    if (slot.status !== 'available') return false;
                    if (!this.isCoverable(slot)) return false;
                    const price = this.calculatePriceForSlot(slot, this.durasi);
                    return price < this.originalHarga;
                },

                // Check if the full duration starting at slot is selectable
                isFullySelectable(slot) {
                    if (slot.status !== 'available') return false;
                    if (this.isPriceTooLow(slot)) return false;
                    const startIdx = this.slots.findIndex(s => s.jam_mulai === slot.jam_mulai);
                    if (startIdx === -1) return false;
                    const slotsNeeded = this.durasi * 2;
                    if (startIdx + slotsNeeded > this.slots.length) return false;
                    for (let i = 1; i < slotsNeeded; i++) {
                        const s = this.slots[startIdx + i];
                        if (!s || s.status !== 'available') return false;
                    }
                    return true;
                },

                selectSlot(slot) {
                    if (!this.isFullySelectable(slot)) return;
                    this.selectedJamMulai = slot.jam_mulai;
                    this.updateCalculatedPrices();
                },

                updateCalculatedPrices() {
                    if (!this.selectedJamMulai) {
                        this.calculatedNewPrice = 0;
                        this.calculatedDiff = 0;
                        return;
                    }
                    const startSlot = this.slots.find(s => s.jam_mulai === this.selectedJamMulai);
                    if (startSlot) {
                        this.calculatedNewPrice = this.calculatePriceForSlot(startSlot, this.durasi);
                        this.calculatedDiff = this.calculatedNewPrice - this.originalHarga;
                    }
                },

                // Slot is in selected range: [selectedIdx, selectedIdx + durasi*2)
                isSlotInRange(slot) {
                    if (!this.selectedJamMulai) return false;
                    const startIdx = this.slots.findIndex(s => s.jam_mulai === this.selectedJamMulai);
                    const currentIdx = this.slots.findIndex(s => s.jam_mulai === slot.jam_mulai);
                    if (startIdx === -1 || currentIdx === -1) return false;
                    return currentIdx >= startIdx && currentIdx < startIdx + (this.durasi * 2);
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

                    if (this.isPriceTooLow(slot)) {
                        return 'border-red-900/50 bg-[#1f1010] text-red-400/60 cursor-not-allowed';
                    }

                    if (this.isSlotInRange(slot)) {
                        if (slot.jam_mulai === this.selectedJamMulai) {
                            return 'border-emerald-400 bg-emerald-400 text-neutral-950 shadow-md shadow-emerald-500/30 scale-105';
                        }
                        return 'border-emerald-600 bg-emerald-700 text-white shadow-sm shadow-emerald-800/30';
                    }

                    if (!this.isFullySelectable(slot)) {
                        return 'border-emerald-900/40 bg-emerald-950/10 text-emerald-700/50 cursor-not-allowed';
                    }

                    return 'border-emerald-700/40 bg-emerald-950/30 text-emerald-300 hover:border-emerald-500 hover:bg-emerald-900/50 hover:scale-105 cursor-pointer';
                },

                getEndTime() {
                    if (!this.selectedJamMulai) return '';
                    const [h, m] = this.selectedJamMulai.split(':').map(Number);
                    const totalMin = (h * 60 + m) + (this.durasi * 60);
                    const fh = Math.floor(totalMin / 60);
                    const fm = totalMin % 60;
                    return String(fh).padStart(2, '0') + ':' + String(fm).padStart(2, '0');
                },
            }
        }
    </script>
</x-app-layout>
