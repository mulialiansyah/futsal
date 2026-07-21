<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notifikasi;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        if ($pembayaran->metode_pembayaran === 'midtrans') {
            return back()->with('error', 'Pembayaran Midtrans hanya dapat dikonfirmasi melalui webhook resmi Midtrans.');
        }

        $validated = $request->validate([
            'status_verifikasi' => 'required|in:diterima,ditolak',
        ]);

        $pembayaran->update($validated);
        $booking = $pembayaran->booking;
        $lapanganNama = $booking->lapangan->nama_lapangan;
        $nominalFormatted = 'Rp '.number_format($pembayaran->nominal, 0, ',', '.');

        if ($validated['status_verifikasi'] === 'diterima') {
            // Reload pembayarans to get updated sum
            $booking->load('pembayarans');

            $totalTerbayar = $booking->total_dibayar;
            $minimalDp = $booking->total_harga * 0.5; // 50% DP rule

            if ($totalTerbayar >= $booking->total_harga) {
                // Lunas
                $booking->update([
                    'status_booking' => 'lunas',
                    'pelunasan_deadline' => null,
                ]);

                Notifikasi::kirim(
                    $booking->user_id,
                    'Pembayaran Diterima (Lunas) ✅',
                    "Pembayaran sebesar {$nominalFormatted} untuk lapangan {$lapanganNama} telah diterima. Status booking Anda sekarang LUNAS.",
                    'pembayaran'
                );

                Notifikasi::kirimKeAdmin(
                    'Booking Lunas 🎉',
                    "Pembayaran sebesar {$nominalFormatted} untuk lapangan {$lapanganNama} (User: {$booking->user->name}) telah terverifikasi LUNAS.",
                    'pembayaran'
                );
            } elseif ($totalTerbayar >= $minimalDp && $booking->status_booking !== 'dp_dibayar') {
                // DP terpenuhi, set batas pelunasan ke waktu main (Hari H jam mulai)
                $tanggalMain = Carbon::parse($booking->tanggal_main->format('Y-m-d').' '.$booking->jam_mulai);
                $pelunasanDeadline = $tanggalMain;

                // Jika ternyata sudah lewat (walaupun tidak mungkin karena validasi booking H-2), amankan saja
                if ($pelunasanDeadline->isPast()) {
                    $pelunasanDeadline = Carbon::now()->addHour();
                }

                $booking->update([
                    'status_booking' => 'dp_dibayar',
                    'pelunasan_deadline' => $pelunasanDeadline,
                ]);

                Notifikasi::kirim(
                    $booking->user_id,
                    'Pembayaran DP Diterima ✅',
                    "Pembayaran DP sebesar {$nominalFormatted} untuk lapangan {$lapanganNama} telah diterima. Sisa pembayaran sewa dapat dilunasi di lokasi.",
                    'pembayaran'
                );

                Notifikasi::kirimKeAdmin(
                    'Booking DP Dibayar ⚽',
                    "Pembayaran DP sebesar {$nominalFormatted} untuk lapangan {$lapanganNama} (User: {$booking->user->name}) telah terverifikasi DP DIBAYAR.",
                    'pembayaran'
                );
            } else {
                Notifikasi::kirim(
                    $booking->user_id,
                    'Pembayaran Diterima ✅',
                    "Pembayaran sebesar {$nominalFormatted} untuk lapangan {$lapanganNama} telah diverifikasi dan diterima.",
                    'pembayaran'
                );
            }
        } else {
            Notifikasi::kirim(
                $booking->user_id,
                'Pembayaran Ditolak ❌',
                "Pembayaran sebesar {$nominalFormatted} ditolak oleh admin. Silakan periksa kembali bukti transfer Anda dan unggah ulang.",
                'pembayaran'
            );
        }

        return redirect()->route('admin.pembayaran.show', $pembayaran)->with('success', 'Pembayaran verified successfully.');
    }

    /**
     * Admin konfirmasi pelunasan cash (customer bayar langsung di tempat)
     */
    public function confirmCash(Request $request, Booking $booking)
    {
        $request->validate([
            'nominal' => 'required|integer|min:1',
        ]);

        // Buat record pembayaran otomatis (tanpa bukti transfer, langsung diterima)
        Pembayaran::create([
            'booking_id' => $booking->id,
            'nominal' => $request->nominal,
            'bukti_transfer' => null, // Cash, tidak ada bukti transfer
            'status_verifikasi' => 'diterima',
        ]);

        // Reload dan hitung ulang
        $booking->load(['pembayarans', 'user', 'lapangan']);
        $totalTerbayar = $booking->total_dibayar;
        $nominalFormatted = 'Rp '.number_format($request->nominal, 0, ',', '.');
        $lapanganNama = $booking->lapangan->nama_lapangan;

        if ($totalTerbayar >= $booking->total_harga) {
            $booking->update([
                'status_booking' => 'lunas',
                'pelunasan_deadline' => null,
            ]);
        }

        Notifikasi::kirim(
            $booking->user_id,
            'Pelunasan Cash Dikonfirmasi ✅',
            "Pembayaran langsung di tempat (Cash) sebesar {$nominalFormatted} untuk sewa lapangan {$lapanganNama} telah dikonfirmasi oleh admin. Status booking: ".ucfirst(str_replace('_', ' ', $booking->fresh()->status_booking)).'.',
            'pembayaran'
        );

        Notifikasi::kirimKeAdmin(
            'Pelunasan Cash Dikonfirmasi 💵',
            "Pelunasan cash sebesar {$nominalFormatted} untuk lapangan {$lapanganNama} (User: {$booking->user->name}) telah dikonfirmasi.",
            'pembayaran'
        );

        return redirect()->route('admin.booking.show', $booking)
            ->with('success', 'Pelunasan cash berhasil dikonfirmasi! Status: '.$booking->fresh()->status_booking);
    }
}
