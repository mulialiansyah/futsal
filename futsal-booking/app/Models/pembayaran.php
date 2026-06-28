<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $fillable = [
        'booking_id',
        'nominal',
        'bukti_transfer',
        'status_verifikasi',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}