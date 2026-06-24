<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkemaHarga extends Model
{
    protected $fillable = ['jam_mulai', 'jam_selesai', 'harga'];
}
