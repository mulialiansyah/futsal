<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class AdminLaporanController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'lapangan_id' => ['nullable', 'integer', 'exists:lapangans,id'],
            'status_booking' => ['nullable', 'in:dp_dibayar,lunas'],
        ]);

        $query = Booking::with(['user', 'lapangan', 'pembayarans']);

        // Filter: Tanggal Mulai & Selesai
        if (! empty($validated['tanggal_mulai'])) {
            $query->whereDate('tanggal_main', '>=', $validated['tanggal_mulai']);
        }

        if (! empty($validated['tanggal_selesai'])) {
            $query->whereDate('tanggal_main', '<=', $validated['tanggal_selesai']);
        }

        // Filter: Lapangan
        if (! empty($validated['lapangan_id'])) {
            $query->where('lapangan_id', $validated['lapangan_id']);
        }

        // Filter: Status (DP Dibayar / Lunas)
        if (! empty($validated['status_booking'])) {
            $query->where('status_booking', $validated['status_booking']);
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
                'nama_lapangan' => $items->first()->lapangan->nama_lapangan,
                'total_booking' => $items->count(),
                'total_pendapatan' => $items->sum('total_harga'),
            ];
        })->sortByDesc('total_booking')->values();

        $lapanganPalingRamai = $ringkasanLapangan->first()['nama_lapangan'] ?? '-';

        $lapangans = Lapangan::orderBy('nama_lapangan')->get();

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
