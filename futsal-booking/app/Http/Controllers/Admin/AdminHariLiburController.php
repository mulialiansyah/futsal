<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HariLibur;
use App\Models\Tarif;
use Illuminate\Http\Request;

class AdminHariLiburController extends Controller
{
    public function index()
    {
        $hariLiburs = HariLibur::orderBy('tanggal')->get();

        // Range harga weekend (berlaku untuk semua hari libur)
        $weekendHarga = Tarif::where('tipe_hari', 'weekend')->pluck('harga');
        $weekendMin   = $weekendHarga->min() ?? 0;
        $weekendMax   = $weekendHarga->max() ?? 0;

        $rangeHargaWeekend = $weekendMin > 0
            ? 'Rp ' . number_format($weekendMin / 1000, 0, ',', '.') . 'k'
              . ($weekendMax !== $weekendMin ? '–' . number_format($weekendMax / 1000, 0, ',', '.') . 'k' : '')
            : '-';

        return view('admin.hari-libur.index', compact('hariLiburs', 'rangeHargaWeekend'));
    }

    public function create()
    {
        return view('admin.hari-libur.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date|unique:hari_liburs,tanggal',
            'keterangan' => 'required|string|max:255',
            'tipe' => 'required|in:nasional,cuti_bersama',
        ]);

        $hariLibur = HariLibur::create($validated);

        if ($request->expectsJson()) {
            return response()->json(['hariLibur' => $this->hariLiburData($hariLibur)], 201);
        }

        return redirect()->route('admin.hari-libur.index')->with('success', 'Hari Libur created successfully.');
    }

    public function show(HariLibur $hariLibur)
    {
        return view('admin.hari-libur.show', compact('hariLibur'));
    }

    public function edit(HariLibur $hariLibur)
    {
        return view('admin.hari-libur.edit', compact('hariLibur'));
    }

    public function update(Request $request, HariLibur $hariLibur)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date|unique:hari_liburs,tanggal,'.$hariLibur->id,
            'keterangan' => 'required|string|max:255',
            'tipe' => 'required|in:nasional,cuti_bersama',
        ]);

        $hariLibur->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['hariLibur' => $this->hariLiburData($hariLibur->fresh())]);
        }

        return redirect()->route('admin.hari-libur.index')->with('success', 'Hari Libur updated successfully.');
    }

    public function destroy(Request $request, HariLibur $hariLibur)
    {
        $hariLibur->delete();

        if ($request->expectsJson()) {
            return response()->noContent();
        }

        return redirect()->route('admin.hari-libur.index')->with('success', 'Hari Libur deleted successfully.');
    }

    private function hariLiburData(HariLibur $hariLibur): array
    {
        return [
            'id' => $hariLibur->id,
            'tanggal' => $hariLibur->tanggal->format('Y-m-d'),
            'keterangan' => $hariLibur->keterangan,
            'tipe' => $hariLibur->tipe,
            'visible' => true,
        ];
    }
}
