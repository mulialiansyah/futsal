<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
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
        $validated = $request->validate([
            'status_verifikasi' => 'required|in:diterima,ditolak',
        ]);

        $pembayaran->update($validated);

        if ($validated['status_verifikasi'] === 'diterima') {
            $pembayaran->booking->update(['status_booking' => 'lunas']);
        }

        return redirect()->route('admin.pembayaran.show', $pembayaran)->with('success', 'Pembayaran verified successfully.');
    }
}
