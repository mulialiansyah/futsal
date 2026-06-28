<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReleaseExpiredBookings extends Command
{
    protected $signature = 'bookings:release-expired';
    protected $description = 'Release expired booking slots yang pending lebih dari deadline pembayaran';

    public function handle()
    {
        // ===== CARI SEMUA BOOKING PENDING YANG SUDAH LEWAT PAYMENT_DEADLINE =====
        $expiredBookings = Booking::where('status_booking', 'pending')
            ->whereNotNull('payment_deadline')
            ->where('payment_deadline', '<', Carbon::now())
            ->get();

        $count = 0;
        foreach ($expiredBookings as $booking) {
            // Mark setiap booking sebagai expired (slot di-release)
            $booking->markAsExpired();
            $count++;
        }

        if ($count > 0) {
            $this->info("✅ {$count} booking(s) berhasil di-release karena expired.");
        } else {
            $this->info("✓ Tidak ada booking yang expired.");
        }
    }
}