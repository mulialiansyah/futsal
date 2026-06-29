<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminLaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $bookings = Booking::with(['user', 'lapangan', 'pembayaran'])
            ->whereBetween('tanggal_main', [$validated['tanggal_mulai'], $validated['tanggal_selesai']])
            ->whereIn('status_booking', ['dp_dibayar', 'lunas'])
            ->latest()
            ->get();

        $totalPendapatan = $bookings->sum('total_harga');

        return view('admin.laporan.show', compact('bookings', 'totalPendapatan', 'validated'));
    }
}
