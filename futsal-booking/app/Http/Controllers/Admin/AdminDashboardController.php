<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Pembayaran;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalBookings = Booking::where('status_booking', 'lunas')->count();
        $totalLapangan = Lapangan::count();
        $pendingPayments = Pembayaran::where('status_verifikasi', 'pending')->count();
        $recentBookings = Booking::with(['user', 'lapangan'])->latest()->take(5)->get();

        // Calculate total revenue for current month
        $totalRevenue = Booking::where('status_booking', 'lunas')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_harga');

        return view('admin.dashboard', compact('totalBookings', 'totalLapangan', 'pendingPayments', 'recentBookings', 'totalRevenue'));
    }
}
