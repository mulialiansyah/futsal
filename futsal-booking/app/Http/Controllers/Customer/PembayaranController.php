<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notifikasi;
use App\Models\Pembayaran;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
        if (! in_array($booking->status_booking, ['pending', 'dp_dibayar'])) {
            return redirect()->route('customer.booking.show', $booking)
                ->with('error', 'Booking ini tidak bisa dibayar (status: '.$booking->status_booking.').');
        }

        // Bukti transfer manual hanya boleh satu yang menunggu verifikasi.
        if ($booking->pembayarans()
            ->where('status_verifikasi', 'pending')
            ->where(function ($query) {
                $query->whereNull('metode_pembayaran')
                    ->orWhere('metode_pembayaran', 'transfer_manual');
            })
            ->exists()) {
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

        $booking->load(['lapangan', 'pembayarans']);

        return view('customer.pembayaran.create', compact('booking'));
    }

    /**
     * Membuat atau memakai kembali token Snap untuk pembayaran online.
     */
    public function createMidtransTransaction(Request $request, Booking $booking, MidtransService $midtrans): JsonResponse
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        $this->ensureBookingCanBePaid($booking);

        $validated = $request->validate([
            'nominal' => ['required', 'integer', 'min:1', 'max:'.$booking->sisa_tagihan],
        ]);
        $minimumPembayaran = $booking->status_booking === 'pending'
            ? (int) ceil($booking->total_harga * 0.5)
            : $booking->sisa_tagihan;

        if ($validated['nominal'] < $minimumPembayaran) {
            throw ValidationException::withMessages([
                'nominal' => ['Pembayaran online minimal Rp '.number_format($minimumPembayaran, 0, ',', '.').'.'],
            ]);
        }

        $pembayaranTertunda = $booking->pembayarans()
            ->where('metode_pembayaran', 'midtrans')
            ->where('status_verifikasi', 'pending')
            ->latest()
            ->first();

        if ($pembayaranTertunda) {
            return response()->json([
                'snap_token' => $pembayaranTertunda->midtrans_snap_token,
                'nominal' => $pembayaranTertunda->nominal,
            ]);
        }

        $booking->load(['lapangan', 'user']);
        $orderId = 'BOOKING-'.$booking->id.'-'.Str::upper(Str::random(10));

        try {
            $snapToken = $midtrans->createSnapToken($booking, $validated['nominal'], $orderId);
        } catch (\Throwable $exception) {
            report($exception);

            throw ValidationException::withMessages([
                'midtrans' => [$exception->getMessage()],
            ]);
        }

        Pembayaran::create([
            'booking_id' => $booking->id,
            'nominal' => $validated['nominal'],
            'metode_pembayaran' => 'midtrans',
            'midtrans_order_id' => $orderId,
            'midtrans_snap_token' => $snapToken,
            'status_verifikasi' => 'pending',
        ]);

        return response()->json([
            'snap_token' => $snapToken,
            'nominal' => $validated['nominal'],
        ]);
    }

    /**
     * Simpan bukti bayar
     */
    public function store(Request $request, Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        if ($booking->pembayarans()->where('status_verifikasi', 'pending')->exists()) {
            return redirect()->route('customer.booking.show', $booking)
                ->with('error', 'Selesaikan atau tunggu hasil pembayaran yang masih diproses terlebih dahulu.');
        }

        $request->validate([
            'nominal' => 'required|integer|min:1',
            'bukti_transfer' => 'required|image|max:5120',
        ], [
            'nominal.required' => 'Masukkan nominal yang kamu transfer.',
            'nominal.min' => 'Nominal tidak valid.',
            'bukti_transfer.required' => 'Upload foto/screenshot bukti transfer.',
            'bukti_transfer.image' => 'File harus berupa gambar.',
            'bukti_transfer.max' => 'Ukuran file maksimal 5MB.',
        ]);

        // Double-check booking masih valid
        if (! in_array($booking->status_booking, ['pending', 'dp_dibayar'])) {
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
            'booking_id' => $booking->id,
            'nominal' => $request->nominal,
            'bukti_transfer' => $path,
            'metode_pembayaran' => 'transfer_manual',
            'status_verifikasi' => 'pending',
        ]);

        $booking->load(['user', 'lapangan']);
        Notifikasi::kirimKeAdmin(
            'Bukti Pembayaran Baru 💳',
            "Customer {$booking->user->name} mengunggah bukti pembayaran sebesar Rp ".number_format($request->nominal, 0, ',', '.')." untuk booking {$booking->lapangan->nama_lapangan}.",
            'pembayaran'
        );

        return redirect()->route('customer.booking.show', $booking)
            ->with('success', 'Bukti pembayaran berhasil diupload! Tunggu verifikasi dari admin.');
    }

    private function ensureBookingCanBePaid(Booking $booking): void
    {
        if (! in_array($booking->status_booking, ['pending', 'dp_dibayar'], true)) {
            throw ValidationException::withMessages([
                'booking' => ['Booking ini tidak dapat dibayar.'],
            ]);
        }

        if ($booking->status_booking === 'pending' && $booking->isExpired()) {
            $booking->markAsExpired();

            throw ValidationException::withMessages([
                'booking' => ['Waktu pembayaran sudah habis. Silakan booking ulang.'],
            ]);
        }

        if ($booking->status_booking === 'dp_dibayar' && $booking->isPelunasanExpired()) {
            $booking->markAsBatal();

            throw ValidationException::withMessages([
                'booking' => ['Batas waktu pelunasan sudah lewat. Booking dibatalkan.'],
            ]);
        }
    }
}
