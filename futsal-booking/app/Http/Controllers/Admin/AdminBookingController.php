<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'lapangan', 'pembayaran'])->latest()->get();
        return view('admin.booking.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'lapangan', 'pembayaran']);
        return view('admin.booking.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status_booking' => 'required|in:pending,dp_dibayar,lunas,expired,batal',
        ]);

        $booking->update($validated);

        return redirect()->route('admin.booking.show', $booking)->with('success', 'Status booking updated successfully.');
    }
}
