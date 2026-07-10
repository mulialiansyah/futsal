<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ketersediaan Lapangan
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid lg:grid-cols-3 gap-6">

                {{-- ===== FORM TUTUP LAPANGAN ===== --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow p-5">
                        <h3 class="font-bold text-gray-800 mb-4 text-base">
                            🔒 Tutup Lapangan
                        </h3>

                        @if($errors->any())
                            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-lg text-xs">
                                @foreach($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.ketersediaan.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="block text-xs font-semibold text-gray-600 mb-1">
                                    Pilih Lapangan
                                </label>
                                <select name="lapangan_id" required
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 @error('lapangan_id') border-red-400 @enderror">
                                    <option value="">— Pilih lapangan —</option>
                                    @foreach($lapangans as $lapangan)
                                        <option value="{{ $lapangan->id }}"
                                                {{ old('lapangan_id') == $lapangan->id ? 'selected' : '' }}>
                                            {{ $lapangan->nama_lapangan }}
                                            ({{ $lapangan->kategori_label }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="block text-xs font-semibold text-gray-600 mb-1">
                                    Tanggal Mulai Tutup
                                </label>
                                <input type="date" name="tanggal_mulai"
                                       value="{{ old('tanggal_mulai', now()->toDateString()) }}"
                                       min="{{ now()->toDateString() }}"
                                       required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 @error('tanggal_mulai') border-red-400 @enderror">
                            </div>

                            <div class="mb-3">
                                <label class="block text-xs font-semibold text-gray-600 mb-1">
                                    Tanggal Selesai Tutup
                                </label>
                                <input type="date" name="tanggal_selesai"
                                       value="{{ old('tanggal_selesai', now()->toDateString()) }}"
                                       min="{{ now()->toDateString() }}"
                                       required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 @error('tanggal_selesai') border-red-400 @enderror">
                                <p class="text-xs text-gray-400 mt-1">
                                    Isi tanggal yang sama kalau cuma 1 hari.
                                </p>
                            </div>

                            <div class="mb-4">
                                <label class="block text-xs font-semibold text-gray-600 mb-1">
                                    Keterangan (opsional)
                                </label>
                                <input type="text" name="keterangan"
                                       value="{{ old('keterangan') }}"
                                       placeholder="Contoh: Renovasi lapangan, Turnamen internal..."
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400">
                            </div>

                            <button type="submit"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-lg text-sm transition">
                                🔒 Tutup Lapangan
                            </button>
                        </form>
                    </div>

                    {{-- Info --}}
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mt-4 text-xs text-yellow-800">
                        <p class="font-bold mb-1">⚠️ Catatan:</p>
                        <ul class="space-y-1 list-disc list-inside">
                            <li>Lapangan yang ditutup tidak bisa dipesan penyewa.</li>
                            <li>Booking yang sudah ada sebelumnya tidak terpengaruh.</li>
                            <li>Klik "Buka Kembali" untuk membatalkan penutupan.</li>
                        </ul>
                    </div>
                </div>

                {{-- ===== DAFTAR PENUTUPAN ===== --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <h3 class="font-bold text-gray-800 text-base">
                                Daftar Penutupan
                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $penutupans->count() }} data penutupan tercatat
                            </p>
                        </div>

                        @if($penutupans->isEmpty())
                            <div class="px-5 py-12 text-center text-gray-400">
                                <div class="text-4xl mb-2">✅</div>
                                <div class="font-semibold text-gray-500">
                                    Semua lapangan sedang terbuka
                                </div>
                                <div class="text-sm mt-1">
                                    Belum ada penutupan yang terjadwal.
                                </div>
                            </div>
                        @else
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 text-gray-500 uppercase text-xs border-b border-gray-100">
                                    <tr>
                                        <th class="px-5 py-3 text-left">Lapangan</th>
                                        <th class="px-5 py-3 text-left">Periode Tutup</th>
                                        <th class="px-5 py-3 text-left">Keterangan</th>
                                        <th class="px-5 py-3 text-left">Status</th>
                                        <th class="px-5 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($penutupans as $penutupan)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-5 py-3">
                                                <div class="font-semibold text-gray-800">
                                                    {{ $penutupan->lapangan->nama_lapangan }}
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    {{ $penutupan->lapangan->kategori_label }}
                                                </div>
                                            </td>
                                            <td class="px-5 py-3 text-gray-600 text-xs">
                                                {{ $penutupan->durasi }}
                                            </td>
                                            <td class="px-5 py-3 text-gray-500 text-xs">
                                                {{ $penutupan->keterangan ?? '-' }}
                                            </td>
                                            <td class="px-5 py-3">
                                                @if($penutupan->is_aktif)
                                                    @if($penutupan->tanggal_mulai->isToday() || $penutupan->tanggal_mulai->isPast())
                                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                                            Sedang Tutup
                                                        </span>
                                                    @else
                                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                                                            Terjadwal
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-semibold">
                                                        Selesai
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-5 py-3 text-center">
                                                <form method="POST"
                                                      action="{{ route('admin.ketersediaan.destroy', $penutupan) }}"
                                                      onsubmit="return confirm('Buka kembali lapangan ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="bg-green-100 text-green-700 hover:bg-green-200 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                                        🔓 Buka Kembali
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
