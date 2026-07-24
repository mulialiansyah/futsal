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
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'harga' => 'required|string|max:30',
        ]);

        $validated['harga'] = $this->normalisasiHarga($validated['harga']);
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
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'harga' => 'required|string|max:30',
        ]);

        $validated['harga'] = $this->normalisasiHarga($validated['harga']);
        $tarif->update($validated);

        return redirect()->route('admin.tarif.index')->with('success', 'Tarif updated successfully.');
    }

    public function destroy(Tarif $tarif)
    {
        $tarif->delete();

        return redirect()->route('admin.tarif.index')->with('success', 'Tarif deleted successfully.');
    }

    private function normalisasiHarga(string $harga): int
    {
        $harga = preg_replace('/\s+/', '', trim($harga));

        // Format Indonesia: 80.000,00 atau 80000,00.
        if (str_contains($harga, ',')) {
            [$harga] = explode(',', $harga, 2);
            // Format desimal titik: 80000.00.
        } elseif (preg_match('/\.\d{1,2}$/', $harga)) {
            $harga = preg_replace('/\.\d{1,2}$/', '', $harga);
        }

        return (int) preg_replace('/\D/', '', $harga);
    }
}
