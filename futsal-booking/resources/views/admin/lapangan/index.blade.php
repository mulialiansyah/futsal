<x-admin-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Daftar Lapangan</h1>
                <a href="{{ route('admin.lapangan.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Tambah Lapangan
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full bg-white border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Nama</th>
                            <th class="px-4 py-2 text-left">Kategori</th>
                            <th class="px-4 py-2 text-left">Jenis</th>
                            <th class="px-4 py-2 text-left">Tipe Venue</th>
                            <th class="px-4 py-2 text-left">Gambar</th>
                            <th class="px-4 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lapangans as $lapangan)
                            <tr class="border-t border-gray-200">
                                <td class="px-4 py-2">{{ $lapangan->nama_lapangan }}</td>
                                <td class="px-4 py-2">{{ ucfirst($lapangan->kategori) }}</td>
                                <td class="px-4 py-2">{{ ucfirst($lapangan->jenis_lapangan) }}</td>
                                <td class="px-4 py-2">{{ ucfirst($lapangan->tipe_venue) }}</td>
                                <td class="px-4 py-2">
                                    @if($lapangan->image)
                                        <img src="{{ Storage::url($lapangan->image) }}" alt="{{ $lapangan->nama_lapangan }}" class="w-20 h-20 object-cover">
                                    @else
                                        <span class="text-gray-400">No Image</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('admin.lapangan.show', $lapangan) }}" class="text-blue-600 hover:underline mr-2">Lihat</a>
                                    <a href="{{ route('admin.lapangan.edit', $lapangan) }}" class="text-green-600 hover:underline mr-2">Edit</a>
                                    <form action="{{ route('admin.lapangan.destroy', $lapangan) }}" method="POST" class="inline">
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
