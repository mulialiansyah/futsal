<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::with(['booking.user', 'booking.lapangan'])
                                 ->latest()
                                 ->get();
        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    public function verifikasi(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:disetujui,ditolak',
        ]);

        $pembayaran->update(['status_verifikasi' => $request->status_verifikasi]);

        // Update status booking juga
        if ($request->status_verifikasi === 'disetujui') {
            $pembayaran->booking->update(['status_booking' => 'dp_dibayar']);
        } elseif ($request->status_verifikasi === 'ditolak') {
            $pembayaran->booking->update(['status_booking' => 'batal']);
        }

        return back()->with('success', 'Status pembayaran berhasil diperbarui!');
    }
}
