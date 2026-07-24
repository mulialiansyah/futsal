<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Notifikasi;
use Carbon\Carbon;
use Illuminate\Console\Command;

class KirimPengingatMain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'futsal:pengingat-main';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim notifikasi pengingat H-1 jadwal main futsal ke customer';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $besok = Carbon::tomorrow()->toDateString();

        $bookings = Booking::with('lapangan')
            ->where('tanggal_main', $besok)
            ->whereIn('status_booking', ['dp_dibayar', 'lunas'])
            ->get();

        $count = 0;

        foreach ($bookings as $b) {
            // Cek apakah sudah pernah dikirimi pengingat H-1 untuk booking ini
            $exists = Notifikasi::where('user_id', $b->user_id)
                ->where('tipe', 'pengingat')
                ->where('pesan', 'like', "%[Booking #{$b->id}]%")
                ->exists();

            if (! $exists) {
                $lapanganNama = $b->lapangan->nama_lapangan;
                $jam = substr($b->jam_mulai, 0, 5);

                Notifikasi::kirim(
                    $b->user_id,
                    'Pengingat Jadwal Main ⚽',
                    "Halo! Mengingatkan bahwa jadwal main Anda di lapangan {$lapanganNama} adalah BESOK jam {$jam}. Harap datang 15 menit lebih awal untuk persiapan. [Booking #{$b->id}]",
                    'pengingat'
                );

                $count++;
            }
        }

        $this->info("Berhasil mengirim {$count} pengingat H-1 jadwal main.");
    }
}
