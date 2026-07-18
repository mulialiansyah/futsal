<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'lapangan', 'pembayarans'])->latest()->get();
        return view('admin.booking.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'lapangan', 'pembayarans']);
        return view('admin.booking.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status_booking' => 'required|in:pending,dp_dibayar,lunas,expired,batal',
        ]);

        $statusSebelumnya = $booking->status_booking;
        $booking->update($validated);

        if ($statusSebelumnya !== $booking->status_booking) {
            $lapanganNama = $booking->lapangan->nama_lapangan;
            $tanggal = \Carbon\Carbon::parse($booking->tanggal_main)->isoFormat('D MMM YYYY');
            $jam = substr($booking->jam_mulai, 0, 5);

            if ($booking->status_booking === 'dp_dibayar') {
                \App\Models\Notifikasi::kirim(
                    $booking->user_id,
                    'Booking Dikonfirmasi ✅',
                    "Booking lapangan {$lapanganNama} ({$tanggal} jam {$jam}) telah dikonfirmasi. Sisa pembayaran sewa dapat dilunasi di lokasi.",
                    'booking'
                );
            } elseif ($booking->status_booking === 'lunas') {
                \App\Models\Notifikasi::kirim(
                    $booking->user_id,
                    'Booking Lunas 🎉',
                    "Booking lapangan {$lapanganNama} ({$tanggal} jam {$jam}) dinyatakan LUNAS. Selamat bermain!",
                    'booking'
                );
            } elseif ($booking->status_booking === 'expired') {
                \App\Models\Notifikasi::kirim(
                    $booking->user_id,
                    'Booking Expired ⏰',
                    "Booking lapangan {$lapanganNama} ({$tanggal} jam {$jam}) kedaluwarsa karena tidak ada pembayaran yang dikonfirmasi dalam batas waktu.",
                    'booking'
                );
            } elseif ($booking->status_booking === 'batal') {
                \App\Models\Notifikasi::kirim(
                    $booking->user_id,
                    'Booking Dibatalkan ❌',
                    "Booking lapangan {$lapanganNama} ({$tanggal} jam {$jam}) telah dibatalkan.",
                    'booking'
                );
            }
        }

        return redirect()->route('admin.booking.show', $booking)->with('success', 'Status booking updated successfully.');
    }
}
