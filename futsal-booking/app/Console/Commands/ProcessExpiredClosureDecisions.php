<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Notifikasi;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessExpiredClosureDecisions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:process-expired-closure-decisions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis mengubah status booking menunggu keputusan customer yang melewati 3x24 jam menjadi menunggu refund';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredBookings = Booking::with(['user', 'lapangan'])
            ->where('status_booking', 'menunggu_keputusan_customer')
            ->where('opsi_deadline', '<=', Carbon::now())
            ->get();

        $count = 0;

        foreach ($expiredBookings as $booking) {
            $booking->markAsMenungguRefund();
            $count++;

            $lapanganNama = $booking->lapangan->nama_lapangan ?? 'Lapangan';
            $tglBooking = $booking->tanggal_main ? $booking->tanggal_main->isoFormat('D MMMM YYYY') : '';

            Notifikasi::kirim(
                $booking->user_id,
                'Batas Waktu Keputusan Berakhir ⌛',
                "Batas waktu 3x24 jam untuk memilih opsi pada booking lapangan {$lapanganNama} (#{$booking->id}) telah berakhir. Status otomatis diubah menjadi Opsi Refund Dana. Admin akan memproses pengembalian dana Anda.",
                'booking'
            );

            Notifikasi::kirimKeAdmin(
                'Auto-Fallback Refund: Deadline Expired ⌛',
                "Customer {$booking->user->name} tidak memilih opsi hingga batas 3x24 jam untuk booking #{$booking->id} ({$lapanganNama}). Status otomatis diubah menjadi Menunggu Refund.",
                'booking'
            );
        }

        $this->info("Berhasil memproses {$count} booking menunggu keputusan yang expired.");

        return Command::SUCCESS;
    }
}
