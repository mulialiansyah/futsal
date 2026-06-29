<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalBookings = Booking::count();
        $totalLapangan = Lapangan::count();
        $pendingPayments = Pembayaran::where('status_verifikasi', 'pending')->count();
        $recentBookings = Booking::with(['user', 'lapangan'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('totalBookings', 'totalLapangan', 'pendingPayments', 'recentBookings'));
    }
}
