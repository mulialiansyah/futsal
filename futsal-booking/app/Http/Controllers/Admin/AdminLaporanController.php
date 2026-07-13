<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminLaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'lapangan', 'pembayarans']);

        // Filter: Tanggal Mulai & Selesai
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal_main', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        // Filter: Lapangan
        if ($request->filled('lapangan_id')) {
            $query->where('lapangan_id', $request->lapangan_id);
        }

        // Filter: Status (DP Dibayar / Lunas)
        if ($request->filled('status_booking')) {
            $query->where('status_booking', $request->status_booking);
        } else {
            // Default untuk laporan biasanya hanya menampilkan yang valid (sudah bayar)
            $query->whereIn('status_booking', ['dp_dibayar', 'lunas']);
        }

        $bookings = $query->latest('tanggal_main')->get();

        // Summary Cards Data
        $totalBooking = $bookings->count();
        $totalPendapatan = $bookings->sum('total_harga');
        $rataRataPerBooking = $totalBooking > 0 ? $totalPendapatan / $totalBooking : 0;

        // Lapangan Paling Ramai & Ringkasan per lapangan
        $ringkasanLapangan = $bookings->groupBy('lapangan_id')->map(function ($items) {
            return [
                'nama_lapangan' => $items->first()->lapangan->nama,
                'total_booking' => $items->count(),
                'total_pendapatan' => $items->sum('total_harga'),
            ];
        })->sortByDesc('total_pendapatan')->values();

        $lapanganPalingRamai = $ringkasanLapangan->first()['nama_lapangan'] ?? '-';

        $lapangans = \App\Models\Lapangan::all();

        return view('admin.laporan.index', compact(
            'bookings',
            'totalBooking',
            'totalPendapatan',
            'rataRataPerBooking',
            'lapanganPalingRamai',
            'ringkasanLapangan',
            'lapangans'
        ));
    }
}
