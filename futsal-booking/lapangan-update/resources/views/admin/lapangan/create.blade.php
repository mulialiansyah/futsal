@extends('layouts.admin')

@section('title', 'Tambah Lapangan')

@section('content')
    <div class="max-w-2xl">
        <a href="{{ route('admin.lapangan.index') }}" class="text-sm text-green-600 hover:underline mb-4 inline-block">← Kembali</a>
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-bold text-gray-700 mb-6">Tambah Lapangan Baru</h3>
            <form method="POST" action="{{ route('admin.lapangan.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Nama Lapangan -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lapangan</label>
                    <input type="text" name="nama_lapangan" value="{{ old('nama_lapangan') }}"
                           placeholder="Contoh: CGV Sport Hall FX"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('nama_lapangan') border-red-400 @enderror">
                    @error('nama_lapangan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="alamat" rows="2" placeholder="Contoh: Jln. Jend. Sudirman No.25, Jakarta Pusat"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('alamat') border-red-400 @enderror">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga per Jam -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga per Jam (Rp)</label>
                    <input type="number" name="harga_per_jam" value="{{ old('harga_per_jam') }}" min="0" step="1000"
                           placeholder="Contoh: 300000"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('harga_per_jam') border-red-400 @enderror">
                    @error('harga_per_jam')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ukuran & Kapasitas -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ukuran Lapangan</label>
                        <input type="text" name="ukuran" value="{{ old('ukuran') }}" placeholder="16.8m x 24.95m"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('ukuran') border-red-400 @enderror">
                        @error('ukuran')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas (orang)</label>
                        <input type="number" name="kapasitas" value="{{ old('kapasitas') }}" min="1" placeholder="14"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('kapasitas') border-red-400 @enderror">
                        @error('kapasitas')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Fasilitas -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fasilitas</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($daftarFasilitas as $fasilitas)
                            <label class="flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="fasilitas[]" value="{{ $fasilitas }}"
                                       {{ in_array($fasilitas, old('fasilitas', [])) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                {{ $fasilitas }}
                            </label>
                        @endforeach
                    </div>
                    @error('fasilitas')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto Lapangan -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Lapangan (minimal 3)</label>
                    <input type="file" name="foto[]" multiple accept="image/png,image/jpeg,image/jpg"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-green-50 file:text-green-700 file:font-medium @error('foto') border-red-400 @enderror">
                    <p class="text-gray-400 text-xs mt-1">Format JPG/PNG, maks 2MB per foto.</p>
                    @error('foto')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @error('foto.*')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2.5 rounded-lg transition w-full">
                    Simpan Lapangan
                </button>
            </form>
        </div>
    </div>
@endsection
