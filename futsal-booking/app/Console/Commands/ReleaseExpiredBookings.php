<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReleaseExpiredBookings extends Command
{
    protected $signature = 'bookings:release-expired';
    protected $description = 'Release expired booking slots yang pending atau gagal pelunasan DP';

    public function handle()
    {
        $countExpired = 0;
        $countBatal = 0;

        // ===== 1. CARI SEMUA BOOKING PENDING YANG SUDAH LEWAT PAYMENT_DEADLINE =====
        $expiredBookings = Booking::where('status_booking', 'pending')
            ->whereNotNull('payment_deadline')
            ->where('payment_deadline', '<', Carbon::now())
            ->get();

        foreach ($expiredBookings as $booking) {
            $booking->markAsExpired();
            $countExpired++;
        }

        // ===== 2. CARI SEMUA BOOKING DP_DIBAYAR YANG SUDAH LEWAT PELUNASAN_DEADLINE =====
        $expiredPelunasan = Booking::where('status_booking', 'dp_dibayar')
            ->whereNotNull('pelunasan_deadline')
            ->where('pelunasan_deadline', '<', Carbon::now())
            ->get();

        foreach ($expiredPelunasan as $booking) {
            $booking->markAsBatal();
            $countBatal++;
        }

        $total = $countExpired + $countBatal;

        if ($total > 0) {
            if ($countExpired > 0) {
                $this->info("✅ {$countExpired} booking(s) pending di-release karena expired.");
            }
            if ($countBatal > 0) {
                $this->info("✅ {$countBatal} booking(s) dibatalkan (uang hangus) karena telat pelunasan.");
            }
        } else {
            $this->info("✓ Tidak ada booking yang expired atau batal.");
        }
    }
}