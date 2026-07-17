<x-admin-layout>

    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-neutral-900">Daftar Tarif</h1>
            <p class="text-sm text-neutral-500 mt-1">
                {{ $tarifs->count() }} skema tarif aktif &middot; berlaku otomatis sesuai kategori, hari, dan jam
            </p>
        </div>
        <a href="{{ route('admin.tarif.create') }}"
           class="inline-flex items-center gap-2 bg-yellow-400 hover:bg-yellow-500 text-neutral-900 font-semibold px-5 py-2.5 rounded-xl transition shadow-sm hover:shadow-md">
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
            'standar' => 'bg-sky-50 text-sky-700 border border-sky-200',
            'internasional' => 'bg-violet-50 text-violet-700 border border-violet-200',
        ];
    @endphp

    <div class="grid md:grid-cols-2 gap-6 items-start">
        @forelse($grouped as $tipeHari => $items)
            @php $meta = $dayMeta[$tipeHari] ?? ['label' => ucfirst($tipeHari), 'desc' => '', 'dot' => 'bg-neutral-400']; @endphp

            <div class="bg-white rounded-2xl border border-neutral-200 overflow-hidden">
                <div class="flex items-center gap-3 px-6 py-4 border-b border-neutral-100 bg-neutral-50/60">
                    <span class="w-2.5 h-2.5 rounded-full {{ $meta['dot'] }}"></span>
                    <span class="font-bold text-neutral-900">{{ $meta['label'] }}</span>
                    @if($meta['desc'])
                        <span class="text-xs text-neutral-400">&middot; {{ $meta['desc'] }}</span>
                    @endif
                    <span class="ml-auto text-xs font-semibold text-neutral-400">{{ $items->count() }} tarif</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-neutral-100">
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-500">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-500">Jam</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-500">Harga / Jam</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-neutral-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-50">
                            @foreach($items as $tarif)
                                <tr class="hover:bg-neutral-50/70 transition">
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $kategoriStyle[$tarif->kategori] ?? 'bg-neutral-100 text-neutral-600 border border-neutral-200' }}">
                                            {{ ucfirst($tarif->kategori) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-1.5 text-neutral-600">
                                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $tarif->jam_mulai }} &ndash; {{ $tarif->jam_selesai }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-base font-bold text-neutral-900">Rp {{ number_format($tarif->harga, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('admin.tarif.edit', $tarif) }}"
                                               title="Edit"
                                               class="p-2 rounded-lg text-neutral-400 hover:text-blue-600 hover:bg-blue-50 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.tarif.destroy', $tarif) }}" method="POST"
                                                  onsubmit="return confirm('Hapus tarif ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus"
                                                        class="p-2 rounded-lg text-neutral-400 hover:text-red-600 hover:bg-red-50 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 4h5a1 1 0 011 1v2h-7V5a1 1 0 011-1z"/>
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
            <div class="bg-white rounded-2xl border border-neutral-200 p-14 text-center">
                <p class="text-neutral-500 text-sm mb-4">Belum ada skema tarif yang dibuat.</p>
                <a href="{{ route('admin.tarif.create') }}" class="inline-flex items-center gap-2 bg-yellow-400 hover:bg-yellow-500 text-neutral-900 font-semibold px-5 py-2.5 rounded-xl transition">
                    Tambah Tarif Pertama
                </a>
            </div>
        @endforelse
    </div>

</x-admin-layout>