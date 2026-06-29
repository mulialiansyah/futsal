<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HariLibur;
use Illuminate\Http\Request;

class AdminHariLiburController extends Controller
{
    public function index()
    {
        $hariLiburs = HariLibur::all();
        return view('admin.hari-libur.index', compact('hariLiburs'));
    }

    public function create()
    {
        return view('admin.hari-libur.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date|unique:hari_librs,tanggal',
            'keterangan' => 'required|string|max:255',
            'tipe' => 'required|in:nasional,cuti_bersama',
        ]);

        HariLibur::create($validated);

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
            'tanggal' => 'required|date|unique:hari_librs,tanggal,'.$hariLibur->id,
            'keterangan' => 'required|string|max:255',
            'tipe' => 'required|in:nasional,cuti_bersama',
        ]);

        $hariLibur->update($validated);

        return redirect()->route('admin.hari-libur.index')->with('success', 'Hari Libur updated successfully.');
    }

    public function destroy(HariLibur $hariLibur)
    {
        $hariLibur->delete();

        return redirect()->route('admin.hari-libur.index')->with('success', 'Hari Libur deleted successfully.');
    }
}
