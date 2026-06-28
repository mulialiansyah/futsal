@extends('layouts.customer')

@section('content')
    <a href="{{ route('customer.booking.index') }}" class="text-sm text-green-600 hover:underline mb-4 inline-block">← Kembali ke Booking</a>

    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-extrabold text-gray-800 mb-6">Buat Booking Baru</h2>

            <form method="POST" action="{{ route('customer.booking.store') }}" id="bookingForm">
                @csrf

                <!-- Pilih Lapangan -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Lapangan</label>
                    <select name="lapangan_id" id="lapanganSelect"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('lapangan_id') border-red-400 @enderror">
                        <option value="">— Pilih lapangan —</option>
                        @foreach($lapangans as $lapangan)
                            <option value="{{ $lapangan->id }}"
                                    data-harga="{{ $lapangan->harga_per_jam }}"
                                    {{ old('lapangan_id') == $lapangan->id ? 'selected' : '' }}>
                                {{ $lapangan->nama_lapangan }} — {{ $lapangan->harga_formatted }}/jam
                            </option>
                        @endforeach
                    </select>
                    @error('lapangan_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Main -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Main</label>
                    <input type="date" name="tanggal_main" value="{{ old('tanggal_main') }}"
                           min="{{ now()->addDays(2)->toDateString() }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('tanggal_main') border-red-400 @enderror">
                    <p class="text-gray-400 text-xs mt-1">* Minimal H-2 (2 hari sebelum main)</p>
                    @error('tanggal_main')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jam Mulai & Selesai -->
                <div class="grid grid-cols-2 gap-4 mb-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                        <select name="jam_mulai" id="jamMulai"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('jam_mulai') border-red-400 @enderror">
                            <option value="">— Pilih —</option>
                            @foreach($jamOperasional as $jam)
                                <option value="{{ $jam }}" {{ old('jam_mulai') == $jam ? 'selected' : '' }}>{{ $jam }}</option>
                            @endforeach
                        </select>
                        @error('jam_mulai')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                        <select name="jam_selesai" id="jamSelesai"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('jam_selesai') border-red-400 @enderror">
                            <option value="">— Pilih —</option>
                            @foreach($jamOperasional as $jam)
                                <option value="{{ $jam }}" {{ old('jam_selesai') == $jam ? 'selected' : '' }}>{{ $jam }}</option>
                            @endforeach
                        </select>
                        @error('jam_selesai')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <p class="text-gray-400 text-xs mb-5">* Bisa pilih durasi lebih dari 1 jam</p>

                <!-- Harga Preview -->
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Estimasi Total Harga:</span>
                        <span class="text-lg font-extrabold text-gray-800" id="totalHarga">Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center border-t border-green-200 pt-2">
                        <span class="text-sm text-gray-600">DP yang harus dibayar (50%):</span>
                        <span class="text-lg font-extrabold text-green-600" id="dpHarga">Rp 0</span>
                    </div>
                    <p class="text-gray-400 text-xs mt-2">* Harga final dihitung ulang otomatis oleh sistem saat booking disimpan.</p>
                </div>

                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-3 rounded-lg transition w-full text-sm">
                    Konfirmasi Booking
                </button>
            </form>
        </div>
    </div>

    <script>
        function hitungEstimasiHarga() {
            const lapanganSelect = document.getElementById('lapanganSelect');
            const jamMulai = document.getElementById('jamMulai').value;
            const jamSelesai = document.getElementById('jamSelesai').value;
            const totalEl = document.getElementById('totalHarga');
            const dpEl = document.getElementById('dpHarga');

            const opt = lapanganSelect.options[lapanganSelect.selectedIndex];
            const harga = opt ? parseInt(opt.dataset.harga || 0) : 0;

            if (!jamMulai || !jamSelesai || !harga) {
                totalEl.textContent = 'Rp 0';
                dpEl.textContent = 'Rp 0';
                return;
            }

            const durasi = parseInt(jamSelesai.split(':')[0]) - parseInt(jamMulai.split(':')[0]);

            if (durasi <= 0) {
                totalEl.textContent = 'Jam selesai harus setelah jam mulai';
                dpEl.textContent = 'Rp 0';
                return;
            }

            const total = durasi * harga;
            totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
            dpEl.textContent = 'Rp ' + Math.floor(total / 2).toLocaleString('id-ID');
        }

        document.getElementById('lapanganSelect').addEventListener('change', hitungEstimasiHarga);
        document.getElementById('jamMulai').addEventListener('change', hitungEstimasiHarga);
        document.getElementById('jamSelesai').addEventListener('change', hitungEstimasiHarga);
    </script>
@endsection