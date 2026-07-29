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

        if ($booking->metode_pembayaran === 'cash') {
            return redirect()->route('customer.booking.show', $booking)
                ->with('error', 'Booking ini dipilih untuk pembayaran cash di lokasi. Pembayaran akan dikonfirmasi oleh admin.');
        }

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
        $midtransPayment = $booking->pembayarans
            ->where('metode_pembayaran', 'midtrans')
            ->where('status_verifikasi', 'pending')
            ->sortByDesc('created_at')
            ->first();

        return view('customer.pembayaran.create', compact('booking', 'midtransPayment'));
    }

    /**
     * Membuat atau memakai kembali token Snap untuk pembayaran online.
     */
    public function createMidtransTransaction(Request $request, Booking $booking, MidtransService $midtrans): JsonResponse
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        if ($booking->metode_pembayaran === 'cash') {
            throw ValidationException::withMessages([
                'booking' => ['Booking ini dipilih untuk pembayaran cash di lokasi.'],
            ]);
        }

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
                'order_id' => $pembayaranTertunda->midtrans_order_id,
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
            'order_id' => $orderId,
        ]);
    }

    /** Simpan screenshot sebagai bukti pendukung untuk transaksi Midtrans yang sama. */
    public function store(Request $request, Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        $request->validate([
            'midtrans_order_id' => 'required|string',
            'bukti_transfer' => 'required|file|mimes:png,jpg,jpeg|max:2048',
        ], [
            'midtrans_order_id.required' => 'Mulai pembayaran melalui Midtrans terlebih dahulu.',
            'bukti_transfer.required' => 'Upload screenshot pembayaran Midtrans.',
            'bukti_transfer.file' => 'File harus berupa dokumen gambar valid.',
            'bukti_transfer.mimes' => 'File harus berformat PNG atau JPG.',
            'bukti_transfer.max' => 'Ukuran file maksimal 2MB.',
        ]);

        $pembayaran = $booking->pembayarans()
            ->where('metode_pembayaran', 'midtrans')
            ->where('midtrans_order_id', $request->input('midtrans_order_id'))
            ->whereIn('status_verifikasi', ['pending', 'diterima'])
            ->first();

        if (! $pembayaran) {
            return back()->withInput()->with('error', 'Transaksi Midtrans tidak ditemukan. Silakan mulai pembayaran kembali.');
        }

        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');
        $pembayaran->update(['bukti_transfer' => $path]);

        $booking->load(['user', 'lapangan']);
        Notifikasi::kirimKeAdmin(
            'Screenshot Pembayaran Midtrans Baru 💳',
            "Customer {$booking->user->name} mengunggah screenshot pembayaran Midtrans sebesar Rp ".number_format($pembayaran->nominal, 0, ',', '.')." untuk booking {$booking->lapangan->nama_lapangan}.",
            'pembayaran'
        );

        return redirect()->route('customer.booking.show', $booking)
            ->with('success', 'Screenshot pembayaran berhasil diunggah. Status pembayaran tetap dikonfirmasi otomatis oleh Midtrans.');
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
