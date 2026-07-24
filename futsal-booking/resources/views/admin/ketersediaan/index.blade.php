<x-admin-layout>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-white">Ketersediaan Lapangan</h1>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-400/20 border border-green-400/30 text-green-400 rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-400/20 border border-red-400/30 text-red-400 rounded-xl">
            @foreach($errors->all() as $error)
                <p class="text-sm">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- ===== FORM TUTUP LAPANGAN ===== --}}
        <div class="lg:col-span-1">
            <div class="bg-white/10 rounded-2xl border border-white/20 overflow-hidden p-6">
                <h3 class="font-semibold text-lg text-white mb-4">🔒 Tutup Lapangan</h3>

                <form method="POST" action="{{ route('admin.ketersediaan.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-neutral-300 mb-1">Pilih Lapangan</label>
                        <select name="lapangan_id" required
                                class="w-full bg-white/10 border border-white/30 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 @error('lapangan_id') border-red-500 @enderror">
                            <option value="" class="bg-white text-neutral-900">— Pilih lapangan —</option>
                            @foreach($lapangans as $lapangan)
                                <option value="{{ $lapangan->id }}"
                                        class="bg-white text-neutral-900"
                                        {{ old('lapangan_id') == $lapangan->id ? 'selected' : '' }}>
                                    {{ $lapangan->nama_lapangan }}
                                    ({{ $lapangan->kategori }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-neutral-300 mb-1">Tanggal Mulai Tutup</label>
                        <input type="date" name="tanggal_mulai"
                               value="{{ old('tanggal_mulai', now()->toDateString()) }}"
                               min="{{ now()->toDateString() }}"
                               required
                               class="w-full bg-white/10 border border-white/30 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 @error('tanggal_mulai') border-red-500 @enderror">
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-neutral-300 mb-1">Tanggal Selesai Tutup</label>
                        <input type="date" name="tanggal_selesai"
                               value="{{ old('tanggal_selesai', now()->toDateString()) }}"
                               min="{{ now()->toDateString() }}"
                               required
                               class="w-full bg-white/10 border border-white/30 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 @error('tanggal_selesai') border-red-500 @enderror">
                        <p class="text-xs text-neutral-400 mt-1">
                            Isi tanggal yang sama kalau cuma 1 hari.
                        </p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-semibold text-neutral-300 mb-1">Keterangan (opsional)</label>
                        <input type="text" name="keterangan"
                               value="{{ old('keterangan') }}"
                               placeholder="Contoh: Renovasi lapangan, Turnamen internal..."
                               class="w-full bg-white/10 border border-white/30 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400">
                    </div>

                    <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-xl text-sm transition">
                        🔒 Tutup Lapangan
                    </button>
                </form>
            </div>

            {{-- Info --}}
            <div class="rounded-xl bg-amber-400/20 border border-amber-400/30 p-4 mt-4 text-xs text-amber-400">
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
            <div class="bg-white/10 rounded-2xl border border-white/20 overflow-hidden">
                <div class="px-6 py-4 border-b border-white/20">
                    <h3 class="font-semibold text-lg text-white">Daftar Penutupan</h3>
                    <p class="text-xs text-neutral-400 mt-0.5">
                        {{ $penutupans->count() }} data penutupan tercatat
                    </p>
                </div>

                @if($penutupans->isEmpty())
                    <div class="px-6 py-12 text-center text-neutral-400">
                        <div class="text-4xl mb-2">✅</div>
                        <div class="font-semibold text-neutral-300">
                            Semua lapangan sedang terbuka
                        </div>
                        <div class="text-sm mt-1">
                            Belum ada penutupan yang terjadwal.
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="border-b border-white/20 bg-white/5">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-300">Lapangan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-300">Periode Tutup</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-300">Keterangan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-300">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-neutral-300">Aksi</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @foreach($penutupans as $penutupan)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-6 py-3.5">
                                            <div class="font-semibold text-white">
                                                {{ $penutupan->lapangan->nama_lapangan }}
                                            </div>
                                            <div class="text-xs text-neutral-400">
                                                {{ $penutupan->lapangan->kategori }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-3.5 text-neutral-300 text-xs">
                                            {{ $penutupan->durasi }}
                                        </td>
                                        <td class="px-6 py-3.5 text-neutral-400 text-xs">
                                            {{ $penutupan->keterangan ?? '-' }}
                                        </td>
                                        <td class="px-6 py-3.5">
                                            @if($penutupan->is_aktif)
                                                @if($penutupan->tanggal_mulai->isToday() || $penutupan->tanggal_mulai->isPast())
                                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold border bg-red-400/20 text-red-400 border-red-400/30">
                                                        Sedang Tutup
                                                    </span>
                                                @else
                                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold border bg-amber-400/20 text-amber-400 border-amber-400/30">
                                                        Terjadwal
                                                    </span>
                                                @endif
                                            @else
                                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold border bg-white/10 text-neutral-300 border-white/20">
                                                    Selesai
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3.5 text-center">
                                            <form method="POST"
                                                  action="{{ route('admin.ketersediaan.destroy', $penutupan) }}"
                                                  data-confirm-message="Buka kembali lapangan ini?">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="bg-green-400/20 text-green-400 hover:bg-green-400/30 px-3 py-1.5 rounded-lg text-xs font-semibold transition border border-green-400/30">
                                                    🔓 Buka Kembali
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
