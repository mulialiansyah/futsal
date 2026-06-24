<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LapanganFoto extends Model
{
    protected $fillable = [
        'lapangan_id',
        'path',
    ];

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }
}
