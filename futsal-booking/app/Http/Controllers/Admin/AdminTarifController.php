<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tarif;
use Illuminate\Http\Request;

class AdminTarifController extends Controller
{
    public function index()
    {
        $tarifs = Tarif::all();
        return view('admin.tarif.index', compact('tarifs'));
    }

    public function create()
    {
        return view('admin.tarif.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'required|in:standar,internasional',
            'tipe_hari' => 'required|in:weekday,weekend',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'harga' => 'required|integer|min:0',
        ]);

        Tarif::create($validated);

        return redirect()->route('admin.tarif.index')->with('success', 'Tarif created successfully.');
    }

    public function show(Tarif $tarif)
    {
        return view('admin.tarif.show', compact('tarif'));
    }

    public function edit(Tarif $tarif)
    {
        return view('admin.tarif.edit', compact('tarif'));
    }

    public function update(Request $request, Tarif $tarif)
    {
        $validated = $request->validate([
            'kategori' => 'required|in:standar,internasional',
            'tipe_hari' => 'required|in:weekday,weekend',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'harga' => 'required|integer|min:0',
        ]);

        $tarif->update($validated);

        return redirect()->route('admin.tarif.index')->with('success', 'Tarif updated successfully.');
    }

    public function destroy(Tarif $tarif)
    {
        $tarif->delete();

        return redirect()->route('admin.tarif.index')->with('success', 'Tarif deleted successfully.');
    }
}
