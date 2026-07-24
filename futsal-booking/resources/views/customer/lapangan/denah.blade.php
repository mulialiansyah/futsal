<x-app-layout>
    @php
        $kategoriDenah = [
            'standar' => ['label' => 'Standar', 'badge' => 'bg-blue-500/15 text-blue-300 border-blue-400/20'],
            'internasional' => ['label' => 'Internasional', 'badge' => 'bg-rose-500/15 text-rose-300 border-rose-400/20'],
        ];
        $statusTampilan = [
            'tersedia' => ['label' => 'Tersedia', 'card' => 'border-emerald-500/50 hover:border-emerald-400', 'dot' => 'bg-emerald-500', 'badge' => 'bg-emerald-500 text-emerald-950'],
            'pending' => ['label' => 'Menunggu bayar', 'card' => 'border-amber-500/45 opacity-85', 'dot' => 'bg-amber-500', 'badge' => 'bg-amber-500/20 text-amber-200'],
            'dipesan' => ['label' => 'Dipesan', 'card' => 'border-red-500/45 opacity-85', 'dot' => 'bg-red-500', 'badge' => 'bg-red-500/20 text-red-200'],
            'tutup' => ['label' => 'Ditutup', 'card' => 'border-slate-600/70 opacity-65', 'dot' => 'bg-slate-500', 'badge' => 'bg-slate-500/25 text-slate-200'],
        ];
    @endphp

    <style>
        @keyframes denahFadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .denah-field-card { opacity: 0; animation: denahFadeInUp .45s ease-out forwards; }
        .denah-field-card:hover { transform: translateY(-2px); }

        input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            filter: invert(1);
            opacity: 1;
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23ffffff' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='m6 9 6 6 6-6'/%3E%3C/svg%3E") !important;
            background-position: right 0.75rem center !important;
            background-repeat: no-repeat !important;
            background-size: 1rem !important;
            padding-right: 2.5rem !important;
        }
    </style>

    <div class="max-w-6xl mx-auto py-6 sm:py-8">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-7">
            <div>
                <a href="{{ route('customer.lapangan.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-white transition mb-2">← Kembali ke daftar lapangan</a>
                <h1 class="text-3xl font-extrabold text-white">Denah Lapangan</h1>
            </div>
            <a href="{{ route('customer.booking.create') }}" class="rounded-lg bg-amber-500 hover:bg-amber-400 text-black text-sm font-semibold px-4 py-2.5 transition">Buat Booking →</a>
        </div>

        <div class="bg-[#11151d] border border-white/5 rounded-xl p-5 sm:p-6 mb-6">
            <form method="GET" action="{{ route('customer.lapangan.denah') }}" class="flex flex-wrap items-end gap-4">
                <label class="block">
                    <span class="block text-xs text-slate-400 mb-1.5">Tanggal main</span>
                    <input type="date" name="tanggal" value="{{ $tanggal }}" min="{{ now()->addDays(2)->toDateString() }}"
                        class="bg-[#0b0d12] border border-white/10 rounded-lg px-3 py-2 text-sm text-white outline-none focus:border-amber-500">
                </label>
                <label class="block">
                    <span class="block text-xs text-slate-400 mb-1.5">Jam mulai</span>
                    <select name="jam" class="bg-[#0b0d12] border border-white/10 rounded-lg px-3 py-2 text-sm text-white outline-none focus:border-amber-500">
                        @foreach ($jamOptions as $jamOption)
                            <option value="{{ $jamOption }}" @selected($jam === $jamOption)>{{ $jamOption }}</option>
                        @endforeach
                    </select>
                </label>
                <button type="submit" class="rounded-lg bg-amber-500 hover:bg-amber-400 text-black text-sm font-semibold px-4 py-2 transition">Cek ketersediaan</button>
            </form>
        </div>

        <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm text-slate-300 mb-4">
            @foreach ($statusTampilan as $tampilan)
                <span class="flex items-center gap-2"><span class="inline-block h-2 w-2 rounded-full {{ $tampilan['dot'] }}"></span>{{ $tampilan['label'] }}</span>
            @endforeach
        </div>

        <div class="bg-blue-500/10 border border-blue-500/20 text-blue-300 text-sm rounded-lg px-4 py-3 mb-8">
            Menampilkan status pada <span class="font-semibold text-white">{{ $tanggalCarbon->isoFormat('dddd, D MMMM YYYY') }}</span> pukul <span class="font-semibold text-white">{{ $jam }}</span>.
        </div>

        <div class="space-y-6">
            @foreach ($kategoriDenah as $kategori => $infoKategori)
                @php
                    $lapanganKategori = $lapangans->where('kategori', $kategori);
                @endphp

                @if ($lapanganKategori->isNotEmpty())
                    <section class="bg-[#11151d] border border-white/5 rounded-xl p-5 sm:p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <span class="text-xs font-bold tracking-wide px-2.5 py-1 rounded-md border {{ $infoKategori['badge'] }}">{{ $infoKategori['label'] }}</span>
                            <span class="text-sm text-slate-400">{{ $lapanganKategori->count() }} lapangan</span>
                        </div>

                        <div class="grid grid-cols-1 min-[440px]:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4">
                            @foreach ($lapanganKategori as $index => $lapangan)
                                @php
                                    $status = $statusLapangan->get($lapangan->id, 'tersedia');
                                    $tampilan = $statusTampilan[$status];
                                    $fotoLapangan = $lapangan->foto_utama?->url;
                                @endphp

                                @if ($status === 'tersedia')
                                    <a href="{{ route('customer.lapangan.show', $lapangan) }}"
                                        class="denah-field-card group overflow-hidden rounded-xl border bg-[#0b0d12] text-center transition duration-200 focus:outline-none focus:ring-2 focus:ring-amber-400 {{ $tampilan['card'] }}"
                                        style="animation-delay: {{ $index * 70 }}ms">
                                @else
                                    <div class="denah-field-card overflow-hidden rounded-xl border bg-[#0b0d12] text-center {{ $tampilan['card'] }}" style="animation-delay: {{ $index * 70 }}ms">
                                @endif
                                    <div class="relative h-28 overflow-hidden bg-slate-800">
                                        <img src="{{ $fotoLapangan }}" alt="Foto {{ $lapangan->nama_lapangan }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">
                                        <div class="absolute inset-0 bg-gradient-to-t from-[#0b0d12] via-transparent to-transparent"></div>
                                        <span class="absolute left-3 top-3 inline-flex h-7 min-w-7 items-center justify-center rounded-full bg-black/55 px-2 text-xs font-bold text-white backdrop-blur">{{ $loop->iteration }}</span>
                                    </div>
                                    <div class="p-4">
                                        <p class="font-semibold text-sm text-white mb-1">{{ $lapangan->nama_lapangan }}</p>
                                        <p class="text-xs text-slate-400 capitalize">{{ $lapangan->jenis_lapangan }} · {{ $lapangan->tipe_venue }}</p>
                                        <span class="inline-block mt-3 rounded-full px-3 py-1 text-xs font-semibold {{ $tampilan['badge'] }}">{{ $tampilan['label'] }}</span>
                                    </div>
                                @if ($status === 'tersedia')
                                    </a>
                                @else
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </section>
                @endif
            @endforeach
        </div>

        <p class="text-center text-xs text-slate-500 mt-6">Klik lapangan berstatus tersedia untuk melihat detail dan melanjutkan booking. Status dapat berubah sewaktu-waktu.</p>
    </div>
</x-app-layout>
