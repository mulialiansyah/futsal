<x-admin-layout>

    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">Daftar Tarif</h1>
            <p class="text-sm text-neutral-400 mt-1">
                {{ $tarifs->count() }} skema tarif aktif &middot; berlaku otomatis sesuai kategori, hari, dan jam
            </p>
        </div>
        <a href="{{ route('admin.tarif.create') }}"
           class="inline-flex items-center gap-2 bg-amber-400 hover:bg-amber-300 text-neutral-950 font-semibold px-5 py-2.5 rounded-xl transition shadow-sm hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Tambah Tarif
        </a>
    </div>

    @php
        $grouped = $tarifs->sortBy('jam_mulai')->groupBy('tipe_hari');
        $dayMeta = [
            'weekday' => ['label' => 'Weekday', 'desc' => 'Senin – Jumat', 'dot' => 'bg-sky-500'],
            'weekend' => ['label' => 'Weekend', 'desc' => 'Sabtu – Minggu', 'dot' => 'bg-rose-500'],
        ];
        $kategoriStyle = [
            'standar' => 'bg-sky-400/20 text-sky-400 border border-sky-400/30',
            'internasional' => 'bg-violet-400/20 text-violet-400 border border-violet-400/30',
        ];
    @endphp

    <div class="grid md:grid-cols-2 gap-6 items-start">
        @forelse($grouped as $tipeHari => $items)
            @php $meta = $dayMeta[$tipeHari] ?? ['label' => ucfirst($tipeHari), 'desc' => '', 'dot' => 'bg-neutral-400']; @endphp

            <div class="bg-white/10 rounded-2xl border border-white/20 overflow-hidden">
                <div class="flex items-center gap-3 px-6 py-4 border-b border-white/20 bg-white/5">
                    <span class="w-2.5 h-2.5 rounded-full {{ $meta['dot'] }}"></span>
                    <span class="font-bold text-white">{{ $meta['label'] }}</span>
                    @if($meta['desc'])
                        <span class="text-xs text-neutral-400">&middot; {{ $meta['desc'] }}</span>
                    @endif
                    <span class="ml-auto text-xs font-semibold text-neutral-400">{{ $items->count() }} tarif</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-white/10">
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-300">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-300">Jam</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-300">Harga / Jam</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-neutral-300">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach($items as $tarif)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $kategoriStyle[$tarif->kategori] ?? 'bg-white/10 text-neutral-300 border border-white/20' }}">
                                            {{ ucfirst($tarif->kategori) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-1.5 text-neutral-300">
                                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $tarif->jam_mulai }} &ndash; {{ $tarif->jam_selesai }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-base font-bold text-white">Rp {{ number_format($tarif->harga, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('admin.tarif.edit', $tarif) }}"
                                               title="Edit"
                                               class="p-2 rounded-lg text-neutral-400 hover:text-sky-400 hover:bg-sky-400/10 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.tarif.destroy', $tarif) }}" method="POST"
                                                  onsubmit="return confirm('Hapus tarif ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus"
                                                        class="p-2 rounded-lg text-neutral-400 hover:text-red-400 hover:bg-red-400/10 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.868a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m9.5-4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="bg-white/10 rounded-2xl border border-white/20 p-14 text-center">
                <p class="text-neutral-400 text-sm mb-4">Belum ada skema tarif yang dibuat.</p>
                <a href="{{ route('admin.tarif.create') }}" class="inline-flex items-center gap-2 bg-amber-400 hover:bg-amber-300 text-neutral-950 font-semibold px-5 py-2.5 rounded-xl transition">
                    Tambah Tarif Pertama
                </a>
            </div>
        @endforelse
    </div>

</x-admin-layout>