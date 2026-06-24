<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\LapanganFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LapanganController extends Controller
{
    /**
     * Daftar fasilitas yang bisa dipilih (dipakai juga di view create/edit).
     */
    public const DAFTAR_FASILITAS = [
        'Tempat Parkir',
        'Full CCTV',
        'Ruang Tunggu',
        'Ruang Ganti',
        'Mushola',
        'Toilet',
        'Kantin',
        'Wifi',
    ];

    public function index()
    {
        $lapangans = Lapangan::withCount('bookings')->latest()->get();
        return view('admin.lapangan.index', compact('lapangans'));
    }

    public function create()
    {
        return view('admin.lapangan.create', [
            'daftarFasilitas' => self::DAFTAR_FASILITAS,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateLapangan($request);

        // Foto minimal 3 saat tambah baru
        $request->validate([
            'foto'   => 'required|array|min:3',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'foto.required' => 'Unggah minimal 3 foto lapangan.',
            'foto.min'      => 'Unggah minimal 3 foto lapangan.',
        ]);

        $lapangan = Lapangan::create($validated);

        foreach ($request->file('foto', []) as $file) {
            $path = $file->store('lapangan', 'public');
            $lapangan->fotos()->create(['path' => $path]);
        }

        return redirect()->route('admin.lapangan.index')
                         ->with('success', 'Lapangan berhasil ditambahkan!');
    }

    public function edit(Lapangan $lapangan)
    {
        $lapangan->load('fotos');
        return view('admin.lapangan.edit', [
            'lapangan'        => $lapangan,
            'daftarFasilitas' => self::DAFTAR_FASILITAS,
        ]);
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $validated = $this->validateLapangan($request);

        // Foto opsional saat update, tapi kalau diisi tetap divalidasi formatnya
        $request->validate([
            'foto'   => 'nullable|array',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $lapangan->update($validated);

        // Hapus foto yang ditandai untuk dihapus
        foreach ($request->input('hapus_foto', []) as $fotoId) {
            $foto = $lapangan->fotos()->find($fotoId);
            if ($foto) {
                Storage::disk('public')->delete($foto->path);
                $foto->delete();
            }
        }

        // Tambah foto baru (kalau ada upload tambahan)
        foreach ($request->file('foto', []) as $file) {
            $path = $file->store('lapangan', 'public');
            $lapangan->fotos()->create(['path' => $path]);
        }

        return redirect()->route('admin.lapangan.index')
                         ->with('success', 'Lapangan berhasil diperbarui!');
    }

    public function destroy(Lapangan $lapangan)
    {
        foreach ($lapangan->fotos as $foto) {
            Storage::disk('public')->delete($foto->path);
        }

        $lapangan->delete();

        return redirect()->route('admin.lapangan.index')
                         ->with('success', 'Lapangan berhasil dihapus!');
    }

    /**
     * Validasi field utama lapangan (di luar foto).
     */
    private function validateLapangan(Request $request): array
    {
        return $request->validate([
            'nama_lapangan' => 'required|string|max:255',
            'alamat'        => 'required|string|max:500',
            'harga_per_jam' => 'required|integer|min:0',
            'ukuran'        => 'nullable|string|max:100',
            'kapasitas'     => 'nullable|integer|min:1',
            'fasilitas'     => 'nullable|array',
            'fasilitas.*'   => 'string|in:' . implode(',', self::DAFTAR_FASILITAS),
        ]);
    }
}
