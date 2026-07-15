<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\HariLibur;
use App\Models\Lapangan;
use App\Models\PenutupanLapangan;
use App\Models\Tarif;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LapanganController extends Controller
{
    /**
     * Browse semua lapangan (grid + search + filter kategori)
     */
    public function index(Request $request)
    {
        $keyword  = $request->get('q');
        $kategori = $request->get('kategori');

        $query = Lapangan::with('fotoUtama');

        if ($keyword) {
            $query->where('nama_lapangan', 'like', "%{$keyword}%");
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $lapangans = $query->orderBy('kategori')->orderBy('nama_lapangan')->get();

        // Deteksi tipe hari ini untuk preview harga
        $today     = Carbon::today();
        $isWeekend = $today->isWeekend()
                     || HariLibur::where('tanggal', $today->toDateString())->exists();
        $tipeHari  = $isWeekend ? 'weekend' : 'weekday';

        // Ambil tarif terendah per kategori untuk preview "Mulai dari Rp ..."
        $tarifPreview = Tarif::whereIn('kategori', ['standar', 'internasional'])
            ->where('tipe_hari', $tipeHari)
            ->selectRaw('kategori, MIN(harga) as harga')
            ->groupBy('kategori')
            ->get()
            ->keyBy('kategori');

        return view('customer.lapangan.index', compact(
            'lapangans', 'keyword', 'kategori', 'tipeHari', 'tarifPreview'
        ));
    }

    /**
     * Detail satu lapangan (galeri foto + spesifikasi + tabel tarif)
     */
    public function show(Lapangan $lapangan)
    {
        $lapangan->load('fotos');

        // Ambil semua tarif untuk kategori lapangan ini
        $tarifs = Tarif::where('kategori', $lapangan->kategori)
            ->orderBy('tipe_hari')
            ->orderBy('jam_mulai')
            ->get();

        return view('customer.lapangan.show', compact('lapangan', 'tarifs'));
    }

    /**
     * Kalender visual slot ketersediaan jam (08:00 - 21:00)
     */
    public function slots(Request $request, Lapangan $lapangan)
    {
        // Default tanggal: besok (karena booking minimal H-2, tapi biar user bisa lihat)
        $tanggal = $request->get('tanggal', Carbon::tomorrow()->toDateString());
        $tanggalCarbon = Carbon::parse($tanggal);

        // Cek apakah lapangan ditutup pada tanggal ini
        $isTutup = PenutupanLapangan::isTutup($lapangan->id, $tanggal);

        // Ambil semua booking aktif untuk lapangan ini pada tanggal tersebut
        $bookings = Booking::where('lapangan_id', $lapangan->id)
            ->where('tanggal_main', $tanggal)
            ->whereIn('status_booking', ['pending', 'dp_dibayar', 'lunas'])
            ->get();

        // Generate slot jam 08:00 - 20:00 (jam mulai terakhir, selesai paling malam 21:00)
        $slots = [];
        for ($jam = 8; $jam <= 20; $jam++) {
            $jamStr       = sprintf('%02d:00', $jam);
            $jamSelesaiStr = sprintf('%02d:00', $jam + 1);

            $status = 'tersedia'; // default
            $bookingInfo = null;

            if ($isTutup) {
                $status = 'tutup';
            } else {
                // Cek apakah ada booking yang overlap dengan jam ini
                foreach ($bookings as $b) {
                    // Booking dari jam_mulai sampai jam_selesai
                    if ($jamStr >= substr($b->jam_mulai, 0, 5) && $jamStr < substr($b->jam_selesai, 0, 5)) {
                        $status = 'dipesan';
                        $bookingInfo = $b;
                        break;
                    }
                }
            }

            $slots[] = [
                'jam'          => $jamStr,
                'jam_selesai'  => $jamSelesaiStr,
                'status'       => $status,
                'booking'      => $bookingInfo,
            ];
        }

        // Deteksi tipe hari untuk info harga
        $isWeekend = $tanggalCarbon->isWeekend()
                     || HariLibur::where('tanggal', $tanggal)->exists();
        $tipeHari  = $isWeekend ? 'weekend' : 'weekday';

        return view('customer.lapangan.slots', compact(
            'lapangan', 'tanggal', 'tanggalCarbon', 'slots', 'isTutup', 'tipeHari'
        ));
    }
}
