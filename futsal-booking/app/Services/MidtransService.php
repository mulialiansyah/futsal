<?php

namespace App\Services;

use App\Models\Booking;
use LogicException;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function createSnapToken(Booking $booking, int $nominal, string $orderId): string
    {
        $this->configure();

        return Snap::getSnapToken([
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $nominal,
            ],
            'item_details' => [[
                'id' => "booking-{$booking->id}",
                'price' => $nominal,
                'quantity' => 1,
                'name' => "Booking {$booking->lapangan->nama_lapangan}",
            ]],
            'customer_details' => [
                'first_name' => $booking->user->name,
                'email' => $booking->user->email,
            ],
        ]);
    }

    private function configure(): void
    {
        $serverKey = config('services.midtrans.server_key');

        if (blank($serverKey) || blank(config('services.midtrans.client_key'))) {
            throw new LogicException('Konfigurasi Midtrans belum lengkap. Isi MIDTRANS_SERVER_KEY dan MIDTRANS_CLIENT_KEY pada file .env.');
        }

        Config::$serverKey = $serverKey;
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        if (filled(config('services.midtrans.notification_url'))) {
            Config::$overrideNotifUrl = config('services.midtrans.notification_url');
        }
    }
}
