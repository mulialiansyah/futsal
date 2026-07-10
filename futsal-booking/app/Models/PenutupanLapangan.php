<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PenutupanLapangan extends Model
{
    protected $fillable = [
        'lapangan_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }

    /**
     * Cek apakah lapangan ditutup pada tanggal tertentu
     */
    public static function isTutup(int $lapanganId, string $tanggal): bool
    {
        return self::where('lapangan_id', $lapanganId)
            ->where('tanggal_mulai', '<=', $tanggal)
            ->where('tanggal_selesai', '>=', $tanggal)
            ->exists();
    }

    /**
     * Accessor: apakah penutupan masih aktif / akan datang
     */
    public function getIsAktifAttribute(): bool
    {
        return $this->tanggal_selesai->greaterThanOrEqualTo(Carbon::today());
    }

    public function getDurasiAttribute(): string
    {
        $mulai   = $this->tanggal_mulai->isoFormat('D MMM YYYY');
        $selesai = $this->tanggal_selesai->isoFormat('D MMM YYYY');

        if ($this->tanggal_mulai->eq($this->tanggal_selesai)) {
            return $mulai;
        }
        return "{$mulai} – {$selesai}";
    }
}
