<x-admin-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Daftar Hari Libur</h1>
                <a href="{{ route('admin.hari-libur.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Tambah Hari Libur
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full bg-white border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Tanggal</th>
                            <th class="px-4 py-2 text-left">Keterangan</th>
                            <th class="px-4 py-2 text-left">Tipe</th>
                            <th class="px-4 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hariLiburs as $hariLibur)
                            <tr class="border-t border-gray-200">
                                <td class="px-4 py-2">{{ $hariLibur->tanggal->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">{{ $hariLibur->keterangan }}</td>
                                <td class="px-4 py-2">{{ ucfirst($hariLibur->tipe) }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('admin.hari-libur.edit', $hariLibur) }}" class="text-green-600 hover:underline mr-2">Edit</a>
                                    <form action="{{ route('admin.hari-libur.destroy', $hariLibur) }}" method="POST" class="inline">
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
