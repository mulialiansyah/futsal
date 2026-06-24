<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'lapangan', 'pembayaran'])
                           ->latest()
                           ->get();
        return view('admin.booking.index', compact('bookings'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status_booking' => 'required|in:pending,dp_dibayar,lunas,batal',
        ]);

        $booking->update(['status_booking' => $request->status_booking]);

        return back()->with('success', 'Status booking berhasil diperbarui!');
    }
}
