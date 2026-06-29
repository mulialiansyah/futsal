<?php

namespace App\Services;

use App\Models\HariLibur;
use App\Models\Tarif;
use Carbon\Carbon;

class PricingService
{
    /**
     * Cek apakah tanggal tertentu dihitung sebagai "weekend"
     * (Sabtu/Minggu ATAU termasuk tanggal merah/cuti bersama).
     */
    public static function isWeekend(Carbon $tanggal): bool
    {
        if ($tanggal->isWeekend()) {
            return true;
        }

        return HariLibur::where('tanggal', $tanggal->toDateString())->exists();
    }

    /**
     * Hitung total harga booking.
     *
     * CATATAN PENTING (simplifikasi yang disengaja):
     * Tarif ditentukan berdasarkan window jam dari JAM MULAI booking.
     * Kalau booking nyebrang window (misal mulai 14:00 durasi 2 jam, nyebrang
     * jam 15:00), seluruh durasi dihitung pakai tarif window jam_mulai.
     * Ini disederhanakan untuk MVP — bisa di-improve nanti kalau perlu
     * pecah harga per-jam sesuai window masing-masing.
     */
    public static function hitungHarga(string $kategori, Carbon $tanggal, Carbon $jamMulai, int $durasiJam): int
    {
        $tipeHari = self::isWeekend($tanggal) ? 'weekend' : 'weekday';

        $tarif = Tarif::where('kategori', $kategori)
            ->where('tipe_hari', $tipeHari)
            ->where('jam_mulai', '<=', $jamMulai->format('H:i:s'))
            ->where('jam_selesai', '>', $jamMulai->format('H:i:s'))
            ->first();

        if (!$tarif) {
            return 0; // Tidak ada tarif yang cocok (seharusnya tidak terjadi kalau data lengkap)
        }

        return $tarif->harga * $durasiJam;
    }
}
