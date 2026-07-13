<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminPembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::with(['booking', 'booking.user', 'booking.lapangan'])->latest()->get();
        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load(['booking', 'booking.user', 'booking.lapangan']);
        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    public function verify(Request $request, Pembayaran $pembayaran)
    {
        $validated = $request->validate([
            'status_verifikasi' => 'required|in:diterima,ditolak',
        ]);

        $pembayaran->update($validated);

        if ($validated['status_verifikasi'] === 'diterima') {
            $booking = $pembayaran->booking;
            
            // Reload pembayarans to get updated sum
            $booking->load('pembayarans');
            
            $totalTerbayar = $booking->total_dibayar;
            $minimalDp = $booking->total_harga * 0.5; // 50% DP rule

            if ($totalTerbayar >= $booking->total_harga) {
                // Lunas
                $booking->update([
                    'status_booking' => 'lunas',
                    'pelunasan_deadline' => null
                ]);
            } elseif ($totalTerbayar >= $minimalDp && $booking->status_booking !== 'dp_dibayar') {
                // DP terpenuhi, set batas pelunasan ke waktu main (Hari H jam mulai)
                $tanggalMain = Carbon::parse($booking->tanggal_main->format('Y-m-d') . ' ' . $booking->jam_mulai);
                $pelunasanDeadline = $tanggalMain;
                
                // Jika ternyata sudah lewat (walaupun tidak mungkin karena validasi booking H-2), amankan saja
                if ($pelunasanDeadline->isPast()) {
                    $pelunasanDeadline = Carbon::now()->addHour();
                }

                $booking->update([
                    'status_booking' => 'dp_dibayar',
                    'pelunasan_deadline' => $pelunasanDeadline
                ]);
            }
        }

        return redirect()->route('admin.pembayaran.show', $pembayaran)->with('success', 'Pembayaran verified successfully.');
    }

    /**
     * Admin konfirmasi pelunasan cash (customer bayar langsung di tempat)
     */
    public function confirmCash(Request $request, \App\Models\Booking $booking)
    {
        $request->validate([
            'nominal' => 'required|integer|min:1',
        ]);

        // Buat record pembayaran otomatis (tanpa bukti transfer, langsung diterima)
        \App\Models\Pembayaran::create([
            'booking_id'        => $booking->id,
            'nominal'           => $request->nominal,
            'bukti_transfer'    => null, // Cash, tidak ada bukti transfer
            'status_verifikasi' => 'diterima',
        ]);

        // Reload dan hitung ulang
        $booking->load('pembayarans');
        $totalTerbayar = $booking->total_dibayar;

        if ($totalTerbayar >= $booking->total_harga) {
            $booking->update([
                'status_booking' => 'lunas',
                'pelunasan_deadline' => null,
            ]);
        }

        return redirect()->route('admin.booking.show', $booking)
            ->with('success', 'Pelunasan cash berhasil dikonfirmasi! Status: ' . $booking->fresh()->status_booking);
    }
}
