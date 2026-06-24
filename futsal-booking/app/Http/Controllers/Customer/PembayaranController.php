<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function create(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        abort_if($booking->status_booking !== 'pending', 403, 'Booking ini sudah diproses.');

        // Hitung DP 50% otomatis
        $nominal_dp = $booking->total_harga / 2;

        return view('customer.pembayaran.create', compact('booking', 'nominal_dp'));
    }

    public function store(Request $request, Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Simpan file ke storage/app/public/bukti_transfer
        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        Pembayaran::create([
            'booking_id'       => $booking->id,
            'nominal_dp'       => $booking->total_harga / 2,
            'bukti_transfer'   => $path,
            'status_verifikasi' => 'menunggu',
        ]);

        return redirect()->route('customer.booking.show', $booking)
                         ->with('success', 'Bukti transfer berhasil diunggah! Menunggu verifikasi admin.');
    }
}
