<x-admin-layout>
    @php
        $tarifPerKategori = $tarifs->sortBy('jam_mulai')->groupBy(['tipe_hari', 'kategori']);
        $categories = [
            'standar' => [
                'label' => 'Standar',
                'description' => 'Lapangan sintetis & vinyl reguler',
                'dot' => 'bg-sky-400',
                'card' => 'border-amber-400/40',
                'button' => 'border-white/15 hover:border-amber-500 hover:bg-amber-500 hover:text-black text-white transition-all duration-200',
            ],
            'internasional' => [
                'label' => 'Internasional',
                'description' => 'Lapangan standar kompetisi',
                'dot' => 'bg-violet-400',
                'card' => 'border-amber-400/40',
                'button' => 'border-white/15 hover:border-amber-500 hover:bg-amber-500 hover:text-black text-white transition-all duration-200',
            ],
        ];
    @endphp

    <div x-data="{ mode: 'weekday' }">
        <div class="mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Daftar Tarif</h1>
                <p class="text-sm text-neutral-400 mt-1">
                    {{ $tarifs->count() }} skema tarif aktif &middot; berlaku otomatis sesuai kategori, hari, dan jam
                </p>
            </div>
        </div>

        <div class="flex justify-center mb-10">
            <div class="inline-flex rounded-full border border-white/10 bg-white/5 p-1">
                <button @click="mode = 'weekday'" type="button" :class="mode === 'weekday' ? 'bg-white text-neutral-950 shadow-sm' : 'text-neutral-400 hover:text-white'" class="rounded-full px-5 py-2 text-sm font-semibold transition">Weekday</button>
                <button @click="mode = 'weekend'" type="button" :class="mode === 'weekend' ? 'bg-white text-neutral-950 shadow-sm' : 'text-neutral-400 hover:text-white'" class="rounded-full px-5 py-2 text-sm font-semibold transition">Weekend</button>
            </div>
        </div>

        <div class="grid items-start gap-6 md:grid-cols-2">
            @foreach($categories as $key => $category)
                <section class="relative rounded-2xl border bg-white/10 p-6 sm:p-8 {{ $category['card'] }}">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="h-2.5 w-2.5 rounded-full {{ $category['dot'] }}"></span>
                        <h2 class="text-xl font-bold text-white">{{ $category['label'] }}</h2>
                    </div>
                    <p class="mb-6 text-sm text-neutral-400">{{ $category['description'] }}</p>

                    <div class="mb-8 min-h-28">
                        @foreach(['weekday', 'weekend'] as $tipeHari)
                            @php $items = $tarifPerKategori->get($tipeHari, collect())->get($key, collect()); @endphp
                            <div x-show="mode === '{{ $tipeHari }}'" x-transition.opacity.duration.150ms x-cloak class="space-y-4">
                                @forelse($items as $tarif)
                                    <div class="group flex items-start justify-between gap-4 rounded-xl px-2 py-1.5 transition hover:bg-white/5">
                                        <div class="flex items-start gap-3">
                                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-7.3 7.3a1 1 0 01-1.4 0L3.3 9.3a1 1 0 111.4-1.4L8 11.2l6.3-6.3a1 1 0 011.4 0z" clip-rule="evenodd"/></svg>
                                            <div>
                                                <p class="text-sm font-medium text-white">{{ $tarif->jam_mulai }} &ndash; {{ $tarif->jam_selesai }}</p>
                                                <p class="text-sm text-neutral-400">Rp {{ number_format($tarif->harga, 0, ',', '.') }} / jam</p>
                                            </div>
                                        </div>
                                        <div class="flex shrink-0 items-center gap-1 opacity-100 transition sm:opacity-0 sm:group-hover:opacity-100 sm:group-focus-within:opacity-100">
                                            <a href="{{ route('admin.tarif.edit', $tarif) }}" title="Edit" aria-label="Edit tarif" class="p-2 rounded-lg text-neutral-400 hover:bg-sky-400/10 hover:text-sky-400 transition">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <form action="{{ route('admin.tarif.destroy', $tarif) }}" method="POST" data-confirm-message="Hapus tarif ini?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus" aria-label="Hapus tarif" class="p-2 rounded-lg text-neutral-400 hover:bg-red-400/10 hover:text-red-400 transition">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.868a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m9.5-4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <p class="rounded-xl border border-dashed border-white/10 px-4 py-5 text-sm text-neutral-500">Belum ada tarif {{ $tipeHari }} untuk kategori ini.</p>
                                @endforelse
                            </div>
                        @endforeach
                    </div>

                    <a href="{{ route('admin.tarif.create') }}" class="block w-full rounded-lg border py-2.5 text-center text-sm font-semibold transition {{ $category['button'] }}">
                        + Tambah Tarif {{ $category['label'] }}
                    </a>
                </section>
            @endforeach
        </div>
    </div>
</x-admin-layout>
