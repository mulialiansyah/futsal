<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    protected $fillable = ['nama_lapangan'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
