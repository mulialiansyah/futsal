@extends('layouts.admin')

@section('title', 'Edit Lapangan')

@section('content')
    <div class="max-w-lg">
        <a href="{{ route('admin.lapangan.index') }}" class="text-sm text-green-600 hover:underline mb-4 inline-block">← Kembali</a>
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-bold text-gray-700 mb-6">Edit Lapangan</h3>
            <form method="POST" action="{{ route('admin.lapangan.update', $lapangan) }}">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lapangan</label>
                    <input type="text" name="nama_lapangan" value="{{ old('nama_lapangan', $lapangan->nama_lapangan) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('nama_lapangan') border-red-400 @enderror">
                    @error('nama_lapangan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2.5 rounded-lg transition w-full">
                    Update Lapangan
                </button>
            </form>
        </div>
    </div>
@endsection
