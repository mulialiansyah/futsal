<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Booking Lapangan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('customer.booking.store') }}" method="POST" id="bookingForm">
                    @csrf

                    <!-- Pilih Lapangan -->
                    <div class="mb-5">
                        <label for="lapanganSelect" class="block text-sm font-bold text-gray-700 mb-2">Pilih Lapangan</label>
                        <select name="lapangan_id" id="lapanganSelect" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('lapangan_id') border-red-400 @enderror">
                            <option value="">— Pilih lapangan —</option>
                            @foreach($lapangans as $lapangan)
                                <option value="{{ $lapangan->id }}"
                                        data-kategori="{{ $lapangan->kategori }}"
                                        {{ old('lapangan_id') == $lapangan->id ? 'selected' : '' }}>
                                    {{ $lapangan->nama_lapangan }} — {{ $lapangan->kategori_label }} ({{ $lapangan->deskripsi_singkat }})
                                </option>
                            @endforeach
                        </select>
                        @error('lapangan_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Main -->
                    <div class="mb-5">
                        <label for="tanggal_main" class="block text-sm font-bold text-gray-700 mb-2">Tanggal Main</label>
                        <input type="date" name="tanggal_main" id="tanggal_main" required
                               value="{{ old('tanggal_main') }}"
                               min="{{ now()->addDays(2)->toDateString() }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('tanggal_main') border-red-400 @enderror">
                        <p class="text-gray-400 text-xs mt-1">* Minimal H-2 (2 hari sebelum main). Harga weekend/tanggal merah berbeda dengan weekday.</p>
                        @error('tanggal_main')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jam Mulai (bebas menit) & Durasi -->
                    <div class="grid grid-cols-2 gap-4 mb-2">
                        <div>
                            <label for="jamMulai" class="block text-sm font-bold text-gray-700 mb-2">Jam Mulai</label>
                            <input type="time" name="jam_mulai" id="jamMulai" required
                                   value="{{ old('jam_mulai') }}"
                                   min="08:00" max="21:00"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('jam_mulai') border-red-400 @enderror">
                            <p class="text-gray-400 text-xs mt-1">* Jam operasional 08:00 - 21:00</p>
                            @error('jam_mulai')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="durasiJam" class="block text-sm font-bold text-gray-700 mb-2">Durasi Main</label>
                            <select name="durasi_jam" id="durasiJam" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('durasi_jam') border-red-400 @enderror">
                                <option value="">— Pilih —</option>
                                @foreach([1, 2, 3, 4] as $jam)
                                    <option value="{{ $jam }}" {{ old('durasi_jam') == $jam ? 'selected' : '' }}>{{ $jam }} Jam</option>
                                @endforeach
                            </select>
                            @error('durasi_jam')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Jam Selesai (auto, read-only display) -->
                    <div class="mb-5">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 flex justify-between items-center">
                            <span class="text-sm text-gray-600">Jam Selesai (otomatis):</span>
                            <span class="font-bold text-gray-800" id="jamSelesaiDisplay">—</span>
                        </div>
                    </div>

                    <!-- Harga Preview -->
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm text-gray-600">Tipe Hari:</span>
                            <span class="text-sm font-semibold text-gray-700" id="tipeHariDisplay">—</span>
                        </div>
                        <div class="flex justify-between items-center mb-2 border-t border-green-200 pt-2">
                            <span class="text-sm text-gray-600">Estimasi Total Harga:</span>
                            <span class="text-lg font-extrabold text-gray-800" id="totalHarga">Rp 0</span>
                        </div>
                        <p class="text-gray-400 text-xs mt-2">* Harga final dihitung ulang otomatis oleh sistem saat booking disimpan.</p>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit"
                                class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-bold px-8 py-3 rounded-lg shadow-md transition duration-200 text-center">
                            Booking Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const TARIFS = @json($tarifs);
        const HOLIDAYS = @json($holidays);

        function isWeekendOrHoliday(dateStr) {
            if (!dateStr) return false;
            const d = new Date(dateStr + 'T00:00:00');
            const day = d.getDay(); // 0 = Minggu, 6 = Sabtu
            if (day === 0 || day === 6) return true;
            return HOLIDAYS.includes(dateStr);
        }

        function cariTarif(kategori, tipeHari, jamMulaiStr) {
            return TARIFS.find(t =>
                t.kategori === kategori &&
                t.tipe_hari === tipeHari &&
                jamMulaiStr >= t.jam_mulai.substring(0, 5) &&
                jamMulaiStr < t.jam_selesai.substring(0, 5)
            );
        }

        function hitungJamSelesaiDanHarga() {
            const lapanganSelect = document.getElementById('lapanganSelect');
            const tanggalMain = document.getElementById('tanggal_main').value;
            const jamMulai = document.getElementById('jamMulai').value; // "HH:MM"
            const durasi = parseInt(document.getElementById('durasiJam').value || 0);

            const selesaiEl = document.getElementById('jamSelesaiDisplay');
            const totalEl = document.getElementById('totalHarga');
            const tipeHariEl = document.getElementById('tipeHariDisplay');

            const opt = lapanganSelect.options[lapanganSelect.selectedIndex];
            const kategori = opt ? opt.dataset.kategori : null;

            if (!jamMulai || !durasi) {
                selesaiEl.textContent = '—';
                totalEl.textContent = 'Rp 0';
                tipeHariEl.textContent = '—';
                return;
            }

            // Hitung jam selesai = jam mulai + durasi jam
            const [h, m] = jamMulai.split(':').map(Number);
            let totalMenit = (h * 60 + m) + (durasi * 60);

            if (totalMenit > 21 * 60) {
                selesaiEl.textContent = 'Melewati jam operasional (tutup 21:00)!';
                selesaiEl.classList.add('text-red-600');
                totalEl.textContent = 'Rp 0';
                return;
            }
            selesaiEl.classList.remove('text-red-600');

            const jamSelesai = Math.floor(totalMenit / 60);
            const menitSelesai = totalMenit % 60;
            const jamSelesaiStr = String(jamSelesai).padStart(2, '0') + ':' + String(menitSelesai).padStart(2, '0');
            selesaiEl.textContent = jamSelesaiStr;

            // Tentukan tipe hari
            const weekend = isWeekendOrHoliday(tanggalMain);
            tipeHariEl.textContent = weekend ? 'Weekend / Tanggal Merah' : 'Weekday';

            if (!kategori || !tanggalMain) {
                totalEl.textContent = 'Rp 0';
                return;
            }

            // Cari tarif yang cocok berdasarkan window jam_mulai
            const tarif = cariTarif(kategori, weekend ? 'weekend' : 'weekday', jamMulai);

            if (!tarif) {
                totalEl.textContent = 'Tarif tidak ditemukan';
                return;
            }

            const total = durasi * tarif.harga;
            totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        document.getElementById('lapanganSelect').addEventListener('change', hitungJamSelesaiDanHarga);
        document.getElementById('tanggal_main').addEventListener('change', hitungJamSelesaiDanHarga);
        document.getElementById('jamMulai').addEventListener('change', hitungJamSelesaiDanHarga);
        document.getElementById('durasiJam').addEventListener('change', hitungJamSelesaiDanHarga);
    </script>
</x-app-layout>
