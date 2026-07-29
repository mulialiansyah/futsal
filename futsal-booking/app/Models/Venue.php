<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $fillable = [
        'name',
        'address',
        'open_time',
        'close_time',
    ];

    public function lapangans()
    {
        return $this->hasMany(Lapangan::class, 'venue_id');
    }
}
