<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminLapanganController extends Controller
{
    public function index()
    {
        $lapangans = Lapangan::all();
        return view('admin.lapangan.index', compact('lapangans'));
    }

    public function create()
    {
        return view('admin.lapangan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lapangan' => 'required|string|max:255',
            'kategori' => 'required|in:standar,internasional',
            'jenis_lapangan' => 'required|in:sintetis,vinyl',
            'tipe_venue' => 'required|in:indoor,outdoor',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('lapangan', 'public');
        }

        Lapangan::create($validated);

        return redirect()->route('admin.lapangan.index')->with('success', 'Lapangan created successfully.');
    }

    public function show(Lapangan $lapangan)
    {
        return view('admin.lapangan.show', compact('lapangan'));
    }

    public function edit(Lapangan $lapangan)
    {
        return view('admin.lapangan.edit', compact('lapangan'));
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $validated = $request->validate([
            'nama_lapangan' => 'required|string|max:255',
            'kategori' => 'required|in:standar,internasional',
            'jenis_lapangan' => 'required|in:sintetis,vinyl',
            'tipe_venue' => 'required|in:indoor,outdoor',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($lapangan->image) {
                Storage::disk('public')->delete($lapangan->image);
            }
            $validated['image'] = $request->file('image')->store('lapangan', 'public');
        }

        $lapangan->update($validated);

        return redirect()->route('admin.lapangan.index')->with('success', 'Lapangan updated successfully.');
    }

    public function destroy(Lapangan $lapangan)
    {
        if ($lapangan->image) {
            Storage::disk('public')->delete($lapangan->image);
        }
        $lapangan->delete();

        return redirect()->route('admin.lapangan.index')->with('success', 'Lapangan deleted successfully.');
    }
}
