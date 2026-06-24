<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Pembayaran;

class DashboardController extends Controller
{
    public function index()
    {
        $totalLapangan    = Lapangan::count();
        $totalBooking     = Booking::count();
        $pendingVerifikasi = Pembayaran::where('status_verifikasi', 'menunggu')->count();
        $bookingTerbaru   = Booking::with(['user', 'lapangan'])
                                   ->latest()
                                   ->take(5)
                                   ->get();

        return view('admin.dashboard', compact(
            'totalLapangan',
            'totalBooking',
            'pendingVerifikasi',
            'bookingTerbaru'
        ));
    }
}
