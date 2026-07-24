<x-admin-layout>
    <div
        x-data="hariLiburPage(@js($hariLiburs->map(fn ($hariLibur) => [
            'id' => $hariLibur->id,
            'tanggal' => $hariLibur->tanggal->format('Y-m-d'),
            'keterangan' => $hariLibur->keterangan,
            'tipe' => $hariLibur->tipe,
            'visible' => true,
        ])->values()))"
    >
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Daftar Hari Libur</h1>
                <p class="mt-1 text-sm text-neutral-400">Kelola tanggal libur nasional dan cuti bersama.</p>
            </div>
            <button @click="openAdd()" type="button" class="bg-amber-400 hover:bg-amber-300 text-neutral-950 font-semibold px-4 py-2.5 rounded-xl transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Tambah Hari Libur
            </button>
        </div>

        <div class="bg-white/10 rounded-2xl border border-white/20 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-white/20 bg-white/5">
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-300">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-300">Keterangan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-300">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-300">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        <template x-for="item in items" :key="item.id">
                            <tr
                                x-show="item.visible"
                                x-transition:leave="transition ease-in duration-300"
                                x-transition:leave-start="opacity-100 translate-x-0"
                                x-transition:leave-end="opacity-0 -translate-x-2"
                                class="hover:bg-white/5 transition"
                            >
                                <td class="px-6 py-3.5 text-white font-medium" x-text="formatTanggal(item.tanggal)"></td>
                                <td class="px-6 py-3.5 text-neutral-300" x-text="item.keterangan"></td>
                                <td class="px-6 py-3.5 text-neutral-300" x-text="formatTipe(item.tipe)"></td>
                                <td class="px-6 py-3.5 whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button @click="openEdit(item)" type="button" title="Edit" aria-label="Edit hari libur" class="p-2 rounded-lg text-neutral-400 hover:text-sky-400 hover:bg-sky-400/10 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button @click.stop="appConfirm(`Hapus hari libur ${formatTanggal(item.tanggal)}?`).then(ok => { if (ok) hapusHariLibur(item) })" type="button" title="Hapus" aria-label="Hapus hari libur" class="p-2 rounded-lg text-neutral-400 hover:text-red-400 hover:bg-red-400/10 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.868a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m9.5-4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002-2h6m3 0V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v1"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="items.length === 0">
                            <td colspan="4" class="px-6 py-10 text-center text-neutral-500">Belum ada data hari libur.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <p x-show="errorMessage" x-cloak x-text="errorMessage" class="mt-4 text-sm text-red-400"></p>

        <div
            x-cloak
            x-show="modalOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @keydown.escape.window="closeModal()"
            @click.self="closeModal()"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 px-4"
        >
            <div
                x-show="modalOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                class="w-full max-w-md rounded-2xl border border-white/10 bg-neutral-900 p-6 shadow-2xl"
            >
                <h2 class="text-lg font-semibold text-white mb-4" x-text="editingItem ? 'Edit Hari Libur' : 'Tambah Hari Libur'"></h2>

                <form @submit.prevent="save()" class="space-y-4" data-no-loading>
                    <div>
                        <label for="tanggal" class="block text-sm text-neutral-300 mb-1.5">Tanggal</label>
                        <input id="tanggal" type="date" x-model="form.tanggal" required class="w-full rounded-lg border border-white/10 bg-neutral-950 px-3 py-2.5 text-sm text-white outline-none focus:border-amber-400">
                    </div>
                    <div>
                        <label for="keterangan" class="block text-sm text-neutral-300 mb-1.5">Keterangan</label>
                        <input id="keterangan" type="text" x-model="form.keterangan" required maxlength="255" placeholder="Contoh: Tahun Baru Masehi" class="w-full rounded-lg border border-white/10 bg-neutral-950 px-3 py-2.5 text-sm text-white outline-none focus:border-amber-400">
                    </div>
                    <div>
                        <label for="tipe" class="block text-sm text-neutral-300 mb-1.5">Tipe</label>
                        <select id="tipe" x-model="form.tipe" class="w-full rounded-lg border border-white/10 bg-neutral-950 px-3 py-2.5 text-sm text-white outline-none focus:border-amber-400">
                            <option value="nasional">Nasional</option>
                            <option value="cuti_bersama">Cuti Bersama</option>
                        </select>
                    </div>

                    <p x-show="formError" x-cloak x-text="formError" class="text-sm text-red-400"></p>

                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="closeModal()" :disabled="saving" class="flex-1 rounded-lg border border-white/15 hover:bg-white/5 text-sm font-medium py-2.5 transition disabled:opacity-50">Batal</button>
                        <button type="submit" :disabled="saving" class="flex-1 rounded-lg bg-amber-400 hover:bg-amber-300 text-neutral-950 text-sm font-semibold py-2.5 transition disabled:opacity-50">
                            <span x-text="saving ? 'Menyimpan...' : (editingItem ? 'Simpan Perubahan' : 'Tambah')"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function hariLiburPage(initialItems) {
            return {
                items: initialItems,
                modalOpen: false,
                editingItem: null,
                form: { tanggal: '', keterangan: '', tipe: 'nasional' },
                formError: '',
                errorMessage: '',
                saving: false,

                openAdd() {
                    this.editingItem = null;
                    this.form = { tanggal: '', keterangan: '', tipe: 'nasional' };
                    this.formError = '';
                    this.modalOpen = true;
                },

                openEdit(item) {
                    this.editingItem = item;
                    this.form = { tanggal: item.tanggal, keterangan: item.keterangan, tipe: item.tipe };
                    this.formError = '';
                    this.modalOpen = true;
                },

                closeModal() {
                    if (!this.saving) this.modalOpen = false;
                },

                formatTanggal(tanggal) {
                    const [tahun, bulan, hari] = tanggal.split('-');
                    return `${hari}/${bulan}/${tahun}`;
                },

                formatTipe(tipe) {
                    return tipe === 'cuti_bersama' ? 'Cuti Bersama' : 'Nasional';
                },

                async save() {
                    this.saving = true;
                    this.formError = '';

                    try {
                        const isEdit = Boolean(this.editingItem);
                        const response = await fetch(isEdit ? `{{ url('admin/hari-libur') }}/${this.editingItem.id}` : `{{ route('admin.hari-libur.store') }}`, {
                            method: isEdit ? 'PATCH' : 'POST',
                            headers: this.headers(),
                            body: JSON.stringify(this.form),
                        });
                        const result = await response.json();

                        if (!response.ok) throw new Error(this.validationMessage(result));

                        if (isEdit) {
                            Object.assign(this.editingItem, result.hariLibur);
                        } else {
                            this.items.push({ ...result.hariLibur, visible: true });
                        }
                        this.modalOpen = false;
                    } catch (error) {
                        this.formError = error.message || 'Data hari libur gagal disimpan.';
                    } finally {
                        this.saving = false;
                    }
                },

                hapusHariLibur(item) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ url('admin/hari-libur') }}/${item.id}`;
                    form.innerHTML = `
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                },

                headers() {
                    return {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    };
                },

                async responseError(response) {
                    const body = await response.text();
                    try {
                        return this.validationMessage(JSON.parse(body));
                    } catch (_) {
                        return 'Data hari libur gagal dihapus. Silakan muat ulang halaman lalu coba lagi.';
                    }
                },

                validationMessage(result) {
                    return result?.message || Object.values(result?.errors || {}).flat().join(' ') || 'Terjadi kesalahan. Silakan coba lagi.';
                },
            };
        }
    </script>
</x-admin-layout>
