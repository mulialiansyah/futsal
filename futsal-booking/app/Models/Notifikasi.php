<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasis';

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Static helper to send a notification to a user.
     *
     * @param int $userId
     * @param string $judul
     * @param string $pesan
     * @param string $tipe
     * @return Notifikasi
     */
    public static function kirim(int $userId, string $judul, string $pesan, string $tipe): self
    {
        return self::create([
            'user_id' => $userId,
            'judul'   => $judul,
            'pesan'   => $pesan,
            'tipe'    => $tipe,
            'is_read' => false,
        ]);
    }
}
