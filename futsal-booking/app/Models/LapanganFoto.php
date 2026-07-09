<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LapanganFoto extends Model
{
    protected $fillable = [
        'lapangan_id',
        'path',
        'is_utama',
    ];

    protected $casts = [
        'is_utama' => 'boolean',
    ];

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}