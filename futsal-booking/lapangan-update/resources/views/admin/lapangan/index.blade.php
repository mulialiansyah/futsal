@extends('layouts.admin')

@section('title', 'Kelola Lapangan')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-700">Daftar Lapangan</h3>
        <a href="{{ route('admin.lapangan.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg transition">
            + Tambah Lapangan
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">Foto</th>
                    <th class="px-6 py-3 text-left">Nama Lapangan</th>
                    <th class="px-6 py-3 text-left">Alamat</th>
                    <th class="px-6 py-3 text-left">Harga/Jam</th>
                    <th class="px-6 py-3 text-left">Total Booking</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($lapangans as $lapangan)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        @if($lapangan->fotos->first())
                            <img src="{{ asset('storage/' . $lapangan->fotos->first()->path) }}"
                                 class="w-14 h-14 object-cover rounded-lg border">
                        @else
                            <div class="w-14 h-14 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-xs">
                                No Foto
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $lapangan->nama_lapangan }}</td>
                    <td class="px-6 py-4 text-gray-500 max-w-xs truncate">{{ $lapangan->alamat ?? '-' }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-700">{{ $lapangan->harga_formatted }}/jam</td>
                    <td class="px-6 py-4 text-gray-500">{{ $lapangan->bookings_count }}</td>
                    <td class="px-6 py-4 flex justify-center gap-2">
                        <a href="{{ route('admin.lapangan.edit', $lapangan) }}"
                           class="bg-yellow-100 text-yellow-700 hover:bg-yellow-200 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.lapangan.destroy', $lapangan) }}"
                              onsubmit="return confirm('Yakin hapus lapangan ini?')">
                            @csrf @method('DELETE')
                            <button class="bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">Belum ada lapangan. Tambahkan lapangan pertama!</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
