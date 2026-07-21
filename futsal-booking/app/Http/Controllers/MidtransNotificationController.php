<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Notifikasi;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MidtransNotificationController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'order_id' => ['required', 'string'],
            'status_code' => ['required', 'string'],
            'gross_amount' => ['required'],
            'transaction_status' => ['required', 'string'],
            'signature_key' => ['required', 'string'],
            'transaction_id' => ['nullable', 'string'],
            'payment_type' => ['nullable', 'string'],
            'fraud_status' => ['nullable', 'string'],
        ]);

        $signature = hash('sha512', $payload['order_id'].$payload['status_code'].$payload['gross_amount'].config('services.midtrans.server_key'));

        abort_unless(hash_equals($signature, $payload['signature_key']), 403, 'Signature Midtrans tidak valid.');

        DB::transaction(function () use ($payload) {
            $pembayaran = Pembayaran::where('midtrans_order_id', $payload['order_id'])
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                hash_equals(number_format($pembayaran->nominal, 2, '.', ''), (string) $payload['gross_amount']),
                422,
                'Nominal notifikasi tidak sesuai.'
            );

            $pembayaran->update([
                'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
                'midtrans_transaction_status' => $payload['transaction_status'],
                'midtrans_payment_type' => $payload['payment_type'] ?? null,
                'midtrans_payload' => $payload,
            ]);

            if (! $this->isSuccessful($payload)) {
                if (in_array($payload['transaction_status'], ['deny', 'cancel', 'expire'], true)) {
                    $pembayaran->update(['status_verifikasi' => 'ditolak']);
                }

                return;
            }

            if ($pembayaran->status_verifikasi === 'diterima') {
                return;
            }

            $pembayaran->update(['status_verifikasi' => 'diterima']);
            $booking = Booking::with(['lapangan', 'user'])->lockForUpdate()->findOrFail($pembayaran->booking_id);
            $totalDibayar = $booking->pembayarans()
                ->where('status_verifikasi', 'diterima')
                ->sum('nominal');

            if ($totalDibayar >= $booking->total_harga) {
                $booking->update([
                    'status_booking' => 'lunas',
                    'pelunasan_deadline' => null,
                ]);
                $judul = 'Pembayaran Midtrans Diterima (Lunas) ✅';
                $pesan = "Pembayaran online untuk lapangan {$booking->lapangan->nama_lapangan} telah diterima. Status booking Anda sekarang LUNAS.";
            } elseif ($totalDibayar >= $booking->total_harga * 0.5 && $booking->status_booking !== 'dp_dibayar') {
                $booking->update([
                    'status_booking' => 'dp_dibayar',
                    'pelunasan_deadline' => Carbon::parse($booking->tanggal_main->format('Y-m-d').' '.$booking->jam_mulai),
                ]);
                $judul = 'Pembayaran DP Midtrans Diterima ✅';
                $pesan = "Pembayaran DP online untuk lapangan {$booking->lapangan->nama_lapangan} telah diterima. Sisa tagihan dapat dilunasi sebelum jadwal bermain.";
            } else {
                $judul = 'Pembayaran Midtrans Diterima ✅';
                $pesan = "Pembayaran online untuk lapangan {$booking->lapangan->nama_lapangan} telah diterima.";
            }

            Notifikasi::kirim($booking->user_id, $judul, $pesan, 'pembayaran');
            Notifikasi::kirimKeAdmin(
                'Pembayaran Midtrans Masuk 💳',
                'Pembayaran online Rp '.number_format($pembayaran->nominal, 0, ',', '.')." untuk {$booking->lapangan->nama_lapangan} telah dikonfirmasi Midtrans.",
                'pembayaran'
            );
        });

        return response()->json(['message' => 'Notifikasi Midtrans diproses.']);
    }

    private function isSuccessful(array $payload): bool
    {
        return $payload['transaction_status'] === 'settlement'
            || ($payload['transaction_status'] === 'capture' && ($payload['fraud_status'] ?? 'accept') === 'accept');
    }
}
