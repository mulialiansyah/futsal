<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    protected $fillable = [
        'nama_lapangan',
        'kategori',         // standar | internasional
        'jenis_lapangan',   // sintetis | vinyl
        'tipe_venue',       // indoor | outdoor
        'image',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function penutupans()
    {
        return $this->hasMany(PenutupanLapangan::class);
    }

    /**
     * Cek apakah lapangan ditutup pada tanggal tertentu
     */
    public function isTutupPada(string $tanggal): bool
    {
        return PenutupanLapangan::isTutup($this->id, $tanggal);
    }

    public function getKategoriLabelAttribute(): string
    {
        return $this->kategori === 'internasional' ? 'Internasional' : 'Standar';
    }

    public function getDeskripsiSingkatAttribute(): string
    {
        $jenis = ucfirst($this->jenis_lapangan ?? '-');
        $tipe  = ucfirst($this->tipe_venue ?? '-');
        return "{$jenis} - {$tipe}";
    }
}
