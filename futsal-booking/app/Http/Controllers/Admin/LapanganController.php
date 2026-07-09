<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\LapanganFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LapanganController extends Controller
{
    public const DAFTAR_FASILITAS = [
        'Tempat Parkir', 'Full CCTV', 'Ruang Ganti',
        'Ruang Tunggu', 'Mushola', 'Toilet', 'Kantin', 'Wifi',
    ];

    // ===== INDEX =====
    public function index()
    {
        $lapangans = Lapangan::with('fotoUtama')->latest()->get();
        return view('admin.lapangan.index', compact('lapangans'));
    }

    // ===== CREATE =====
    public function create()
    {
        return view('admin.lapangan.create', [
            'daftarFasilitas' => self::DAFTAR_FASILITAS,
        ]);
    }

    // ===== STORE =====
    public function store(Request $request)
    {
        $request->validate([
            'nama_lapangan'  => 'required|string|max:100',
            'kategori'       => 'required|in:standar,internasional',
            'jenis_lapangan' => 'required|in:sintetis,vinyl',
            'tipe_venue'     => 'required|in:indoor,outdoor',
            'foto'           => 'required|array|min:1',
            'foto.*'         => 'image|mimes:jpg,jpeg,png,webp|max:3072',
            'foto_utama'     => 'nullable|integer',
        ], [
            'foto.required' => 'Upload minimal 1 foto lapangan.',
            'foto.*.image'  => 'File harus berupa gambar.',
            'foto.*.max'    => 'Ukuran foto maksimal 3MB.',
        ]);

        $lapangan = Lapangan::create($request->only([
            'nama_lapangan', 'kategori', 'jenis_lapangan', 'tipe_venue',
        ]));

        // Upload semua foto
        $files = $request->file('foto');
        foreach ($files as $index => $file) {
            $path = $file->store('lapangan', 'public');
            $lapangan->fotos()->create([
                'path'     => $path,
                'is_utama' => ($index === 0), // foto pertama jadi utama
            ]);
        }

        return redirect()->route('admin.lapangan.index')
                         ->with('success', "Lapangan \"{$lapangan->nama_lapangan}\" berhasil ditambahkan!");
    }

    // ===== EDIT =====
    public function edit(Lapangan $lapangan)
    {
        $lapangan->load('fotos');
        return view('admin.lapangan.edit', [
            'lapangan'        => $lapangan,
            'daftarFasilitas' => self::DAFTAR_FASILITAS,
        ]);
    }

    // ===== UPDATE =====
    public function update(Request $request, Lapangan $lapangan)
    {
        $request->validate([
            'nama_lapangan'  => 'required|string|max:100',
            'kategori'       => 'required|in:standar,internasional',
            'jenis_lapangan' => 'required|in:sintetis,vinyl',
            'tipe_venue'     => 'required|in:indoor,outdoor',
            'foto'           => 'nullable|array',
            'foto.*'         => 'image|mimes:jpg,jpeg,png,webp|max:3072',
            'hapus_foto'     => 'nullable|array',
            'foto_utama'     => 'nullable|integer|exists:lapangan_fotos,id',
        ]);

        $lapangan->update($request->only([
            'nama_lapangan', 'kategori', 'jenis_lapangan', 'tipe_venue',
        ]));

        // Hapus foto yang ditandai
        if ($request->has('hapus_foto')) {
            foreach ($request->hapus_foto as $fotoId) {
                $foto = LapanganFoto::find($fotoId);
                if ($foto && $foto->lapangan_id === $lapangan->id) {
                    Storage::disk('public')->delete($foto->path);
                    $foto->delete();
                }
            }
        }

        // Upload foto baru
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('lapangan', 'public');
                $lapangan->fotos()->create([
                    'path'     => $path,
                    'is_utama' => false,
                ]);
            }
        }

        // Set foto utama
        if ($request->filled('foto_utama')) {
            $lapangan->fotos()->update(['is_utama' => false]);
            LapanganFoto::where('id', $request->foto_utama)
                        ->where('lapangan_id', $lapangan->id)
                        ->update(['is_utama' => true]);
        } elseif ($lapangan->fotos()->where('is_utama', true)->doesntExist()) {
            // Kalau belum ada foto utama, set foto pertama
            $lapangan->fotos()->oldest()->first()?->update(['is_utama' => true]);
        }

        return redirect()->route('admin.lapangan.index')
                         ->with('success', "Lapangan \"{$lapangan->nama_lapangan}\" berhasil diperbarui!");
    }

    // ===== DESTROY =====
    public function destroy(Lapangan $lapangan)
    {
        foreach ($lapangan->fotos as $foto) {
            Storage::disk('public')->delete($foto->path);
        }
        $lapangan->delete();

        return redirect()->route('admin.lapangan.index')
                         ->with('success', 'Lapangan berhasil dihapus.');
    }
}