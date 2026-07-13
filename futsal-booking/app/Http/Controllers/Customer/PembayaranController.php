<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    /**
     * Form upload bukti bayar
     */
    public function create(Booking $booking)
    {
        // Pastikan booking milik user ini
        abort_if($booking->user_id !== Auth::id(), 403);

        // Pastikan status masih valid untuk dibayar
        if (!in_array($booking->status_booking, ['pending', 'dp_dibayar'])) {
            return redirect()->route('customer.booking.show', $booking)
                ->with('error', 'Booking ini tidak bisa dibayar (status: ' . $booking->status_booking . ').');
        }

        // Pastikan tidak ada pembayaran yang masih pending verifikasi
        if ($booking->pembayarans()->where('status_verifikasi', 'pending')->exists()) {
            return redirect()->route('customer.booking.show', $booking)
                ->with('error', 'Ada bukti pembayaran yang sedang menunggu verifikasi admin.');
        }

        // Cek Expired DP
        if ($booking->status_booking === 'pending' && $booking->isExpired()) {
            $booking->markAsExpired();
            return redirect()->route('customer.booking.index')
                ->with('error', 'Waktu pembayaran sudah habis. Silakan booking ulang.');
        }

        // Cek Expired Pelunasan
        if ($booking->status_booking === 'dp_dibayar' && $booking->isPelunasanExpired()) {
            $booking->markAsBatal();
            return redirect()->route('customer.booking.index')
                ->with('error', 'Batas waktu pelunasan sudah lewat. Booking dibatalkan.');
        }

        $booking->load('lapangan');
        return view('customer.pembayaran.create', compact('booking'));
    }

    /**
     * Simpan bukti bayar
     */
    public function store(Request $request, Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        $request->validate([
            'nominal'        => 'required|integer|min:1',
            'bukti_transfer' => 'required|image|max:5120',
        ], [
            'nominal.required'        => 'Masukkan nominal yang kamu transfer.',
            'nominal.min'             => 'Nominal tidak valid.',
            'bukti_transfer.required' => 'Upload foto/screenshot bukti transfer.',
            'bukti_transfer.image'    => 'File harus berupa gambar.',
            'bukti_transfer.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        // Double-check booking masih valid
        if (!in_array($booking->status_booking, ['pending', 'dp_dibayar'])) {
            return redirect()->route('customer.booking.index')
                ->with('error', 'Booking tidak valid.');
        }

        if ($booking->status_booking === 'pending' && $booking->isExpired()) {
            return redirect()->route('customer.booking.index')
                ->with('error', 'Waktu pembayaran sudah habis. Silakan booking ulang.');
        }

        if ($booking->status_booking === 'dp_dibayar' && $booking->isPelunasanExpired()) {
            return redirect()->route('customer.booking.index')
                ->with('error', 'Batas waktu pelunasan sudah lewat. Booking dibatalkan.');
        }

        // Upload foto bukti transfer
        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        Pembayaran::create([
            'booking_id'       => $booking->id,
            'nominal'          => $request->nominal,
            'bukti_transfer'   => $path,
            'status_verifikasi' => 'pending',
        ]);

        return redirect()->route('customer.booking.show', $booking)
            ->with('success', 'Bukti pembayaran berhasil diupload! Tunggu verifikasi dari admin.');
    }
}
