<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $lapangans = Lapangan::orderBy('nama_lapangan')->get();

        $query = Booking::with(['user', 'lapangan', 'pembayarans'])
            ->whereIn('status_booking', ['dp_dibayar', 'lunas']);

        // Filter tanggal
        if ($request->filled('dari_tanggal')) {
            $query->where('tanggal_main', '>=', $request->dari_tanggal);
        }
        if ($request->filled('sampai_tanggal')) {
            $query->where('tanggal_main', '<=', $request->sampai_tanggal);
        }

        // Filter lapangan (opsional)
        if ($request->filled('lapangan_id')) {
            $query->where('lapangan_id', $request->lapangan_id);
        }

        // Filter status (opsional)
        if ($request->filled('status')) {
            $query->where('status_booking', $request->status);
        }

        $bookings = $query->orderBy('tanggal_main')->get();

        // Hitung total pendapatan
        $totalPendapatan = $bookings->sum('total_harga');
        $totalBooking = $bookings->count();

        // Summary per lapangan
        $perLapangan = $bookings->groupBy('lapangan.nama_lapangan')
            ->map(fn ($items) => [
                'jumlah' => $items->count(),
                'pendapatan' => $items->sum('total_harga'),
            ]);

        return view('admin.laporan.index', compact(
            'bookings',
            'lapangans',
            'totalPendapatan',
            'totalBooking',
            'perLapangan',
        ));
    }
}
