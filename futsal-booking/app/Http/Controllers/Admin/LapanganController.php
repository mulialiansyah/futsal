<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class LapanganController extends Controller
{
    public function index()
    {
        $lapangans = Lapangan::latest()->get();
        return view('admin.lapangan.index', compact('lapangans'));
    }

    public function create()
    {
        return view('admin.lapangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lapangan' => 'required|string|max:255',
        ]);

        Lapangan::create($request->only('nama_lapangan'));

        return redirect()->route('admin.lapangan.index')
                         ->with('success', 'Lapangan berhasil ditambahkan!');
    }

    public function edit(Lapangan $lapangan)
    {
        return view('admin.lapangan.edit', compact('lapangan'));
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $request->validate([
            'nama_lapangan' => 'required|string|max:255',
        ]);

        $lapangan->update($request->only('nama_lapangan'));

        return redirect()->route('admin.lapangan.index')
                         ->with('success', 'Lapangan berhasil diperbarui!');
    }

    public function destroy(Lapangan $lapangan)
    {
        $lapangan->delete();
        return redirect()->route('admin.lapangan.index')
                         ->with('success', 'Lapangan berhasil dihapus!');
    }
}
