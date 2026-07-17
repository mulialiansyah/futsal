<x-admin-layout>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-neutral-900">Daftar Hari Libur</h1>
        <a href="{{ route('admin.hari-libur.create') }}" class="bg-yellow-400 hover:bg-yellow-500 text-neutral-900 font-semibold px-4 py-2 rounded-xl transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Tambah Hari Libur
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-neutral-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-neutral-200 bg-neutral-50">
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Keterangan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach($hariLiburs as $hariLibur)
                        <tr class="hover:bg-neutral-50 transition">
                            <td class="px-6 py-3.5 text-neutral-900 font-medium">{{ $hariLibur->tanggal->format('d/m/Y') }}</td>
                            <td class="px-6 py-3.5 text-neutral-700">{{ $hariLibur->keterangan }}</td>
                            <td class="px-6 py-3.5 text-neutral-700">{{ ucfirst($hariLibur->tipe) }}</td>
                            <td class="px-6 py-3.5">
                                <a href="{{ route('admin.hari-libur.edit', $hariLibur) }}" class="text-blue-600 hover:text-blue-800 mr-3 transition font-medium">Edit</a>
                                <form action="{{ route('admin.hari-libur.destroy', $hariLibur) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition font-medium" onclick="return confirm('Are you sure?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
