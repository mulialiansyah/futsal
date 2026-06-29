<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    protected $fillable = [
        'kategori',
        'tipe_hari',
        'jam_mulai',
        'jam_selesai',
        'harga',
    ];
}
