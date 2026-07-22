<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    public function fotos()
    {
        // Return a dummy collection since we use single image column
        return $this->hasMany(LapanganFoto::class);
    }

    public function getFotoUtamaAttribute()
    {
        if ($this->image) {
            return (object)[
                'url' => Storage::url($this->image)
            ];
        }

        // Foto fallback tersimpan lokal agar halaman tetap tampil saat offline.
        $defaultPhotos = [
            1 => 'lapangan/8uT3He187BRuTMVSesGBLx2gP1NWpBIoSLtAy2Nh.webp',
            2 => 'lapangan/d4v4OhP50qjFer95nIS20Kwom7OYNOgzwknE7qcx.jpg',
            3 => 'lapangan/eO9eyj9fvhX28CVCY1GSoP9SMJq9mJ6PVyRgIBzR.jpg',
            4 => 'lapangan/j4TQ2Xo3LaB7IxkKtpuB1t7EYJdWGj7TRkjXktfl.jpg',
            5 => 'lapangan/jSwMo6BwKW0Z7L5BiyGnoghOynPg0BfubDMuAN6c.jpg',
            6 => 'lapangan/OgIsndrcINcFKVtPLwvJ29QQEx9RqTlIQh0t2IgQ.webp',
            7 => 'lapangan/R5fHZnQ0FoQ6k82EcfMt54v3rRZamh1fB3s6tzz2.jpg',
            8 => 'lapangan/xU6tVjScJPYpxmBfk2kkbUEkZBbFoWVFSvzar9aE.jpg',
            9 => 'lapangan/zSimQvOCLlugW5gin3koD6RAPfr74lXkKvGBU05w.webp',
        ];

        return (object)[
            'url' => Storage::url($defaultPhotos[$this->id] ?? $defaultPhotos[1])
        ];
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
