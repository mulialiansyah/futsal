<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    protected $fillable = [
        'nama_lapangan',
        'alamat',
        'harga_per_jam',
        'ukuran',
        'kapasitas',
        'fasilitas',
    ];

    protected $casts = [
        'fasilitas' => 'array',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getHargaFormattedAttribute(): string
    {
        return $this->harga_per_jam
            ? 'Rp ' . number_format($this->harga_per_jam, 0, ',', '.')
            : '-';
    }
}