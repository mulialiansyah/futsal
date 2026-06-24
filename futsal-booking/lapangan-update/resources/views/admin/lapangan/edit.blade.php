@extends('layouts.admin')

@section('title', 'Edit Lapangan')

@section('content')
    <div class="max-w-2xl">
        <a href="{{ route('admin.lapangan.index') }}" class="text-sm text-green-600 hover:underline mb-4 inline-block">← Kembali</a>
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-bold text-gray-700 mb-6">Edit Lapangan</h3>
            <form method="POST" action="{{ route('admin.lapangan.update', $lapangan) }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                <!-- Nama Lapangan -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lapangan</label>
                    <input type="text" name="nama_lapangan" value="{{ old('nama_lapangan', $lapangan->nama_lapangan) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('nama_lapangan') border-red-400 @enderror">
                    @error('nama_lapangan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="alamat" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('alamat') border-red-400 @enderror">{{ old('alamat', $lapangan->alamat) }}</textarea>
                    @error('alamat')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga per Jam -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga per Jam (Rp)</label>
                    <input type="number" name="harga_per_jam" value="{{ old('harga_per_jam', $lapangan->harga_per_jam) }}" min="0" step="1000"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('harga_per_jam') border-red-400 @enderror">
                    @error('harga_per_jam')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ukuran & Kapasitas -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ukuran Lapangan</label>
                        <input type="text" name="ukuran" value="{{ old('ukuran', $lapangan->ukuran) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('ukuran') border-red-400 @enderror">
                        @error('ukuran')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas (orang)</label>
                        <input type="number" name="kapasitas" value="{{ old('kapasitas', $lapangan->kapasitas) }}" min="1"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('kapasitas') border-red-400 @enderror">
                        @error('kapasitas')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Fasilitas -->
                @php $selectedFasilitas = old('fasilitas', $lapangan->fasilitas ?? []); @endphp
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fasilitas</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($daftarFasilitas as $fasilitas)
                            <label class="flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="fasilitas[]" value="{{ $fasilitas }}"
                                       {{ in_array($fasilitas, $selectedFasilitas) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                {{ $fasilitas }}
                            </label>
                        @endforeach
                    </div>
                    @error('fasilitas')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto Lapangan Saat Ini -->
                @if($lapangan->fotos->count())
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Saat Ini</label>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach($lapangan->fotos as $foto)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $foto->path) }}" class="w-full h-24 object-cover rounded-lg border">
                                <label class="absolute top-1 right-1 bg-white/90 rounded-full p-1 cursor-pointer">
                                    <input type="checkbox" name="hapus_foto[]" value="{{ $foto->id }}" class="accent-red-600">
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-gray-400 text-xs mt-1">Centang foto yang ingin dihapus.</p>
                </div>
                @endif

                <!-- Tambah Foto Baru -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tambah Foto Baru (opsional)</label>
                    <input type="file" name="foto[]" multiple accept="image/png,image/jpeg,image/jpg"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-green-50 file:text-green-700 file:font-medium @error('foto') border-red-400 @enderror">
                    @error('foto.*')
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
