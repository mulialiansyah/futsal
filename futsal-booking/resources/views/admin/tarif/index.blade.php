<x-admin-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Daftar Tarif</h1>
                <a href="{{ route('admin.tarif.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Tambah Tarif
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full bg-white border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Kategori</th>
                            <th class="px-4 py-2 text-left">Tipe Hari</th>
                            <th class="px-4 py-2 text-left">Jam Mulai</th>
                            <th class="px-4 py-2 text-left">Jam Selesai</th>
                            <th class="px-4 py-2 text-left">Harga</th>
                            <th class="px-4 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tarifs as $tarif)
                            <tr class="border-t border-gray-200">
                                <td class="px-4 py-2">{{ ucfirst($tarif->kategori) }}</td>
                                <td class="px-4 py-2">{{ ucfirst($tarif->tipe_hari) }}</td>
                                <td class="px-4 py-2">{{ $tarif->jam_mulai }}</td>
                                <td class="px-4 py-2">{{ $tarif->jam_selesai }}</td>
                                <td class="px-4 py-2">Rp {{ number_format($tarif->harga, 0, ',', '.') }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('admin.tarif.edit', $tarif) }}" class="text-green-600 hover:underline mr-2">Edit</a>
                                    <form action="{{ route('admin.tarif.destroy', $tarif) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
