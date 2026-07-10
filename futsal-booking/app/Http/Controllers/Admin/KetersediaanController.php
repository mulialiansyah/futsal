<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\PenutupanLapangan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KetersediaanController extends Controller
{
    public function index()
    {
        $lapangans   = Lapangan::orderBy('nama_lapangan')->get();
        $penutupans  = PenutupanLapangan::with('lapangan')
                           ->orderByDesc('tanggal_mulai')
                           ->get();

        return view('admin.ketersediaan.index', compact('lapangans', 'penutupans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lapangan_id'     => 'required|exists:lapangans,id',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan'      => 'nullable|string|max:200',
        ], [
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',
        ]);

        // Cek apakah sudah ada penutupan yang overlap
        $overlap = PenutupanLapangan::where('lapangan_id', $request->lapangan_id)
            ->where('tanggal_mulai', '<=', $request->tanggal_selesai)
            ->where('tanggal_selesai', '>=', $request->tanggal_mulai)
            ->exists();

        if ($overlap) {
            return back()
                ->withErrors(['tanggal_mulai' => 'Lapangan ini sudah ditutup pada rentang tanggal tersebut.'])
                ->withInput();
        }

        PenutupanLapangan::create($request->only([
            'lapangan_id',
            'tanggal_mulai',
            'tanggal_selesai',
            'keterangan',
        ]));

        $lapangan = Lapangan::find($request->lapangan_id);
        return redirect()->route('admin.ketersediaan.index')
                         ->with('success', "Lapangan \"{$lapangan->nama_lapangan}\" berhasil ditutup!");
    }

    public function destroy(PenutupanLapangan $ketersediaan)
    {
        $namaLapangan = $ketersediaan->lapangan->nama_lapangan;
        $ketersediaan->delete();

        return redirect()->route('admin.ketersediaan.index')
                         ->with('success', "Penutupan lapangan \"{$namaLapangan}\" berhasil dihapus. Lapangan kembali tersedia.");
    }
}
