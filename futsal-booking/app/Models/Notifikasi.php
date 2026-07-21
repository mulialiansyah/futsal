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
     */
    public static function kirim(int $userId, string $judul, string $pesan, string $tipe): self
    {
        return self::create([
            'user_id' => $userId,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'is_read' => false,
        ]);
    }

    /**
     * Static helper to send a notification to all admin users.
     */
    public static function kirimKeAdmin(string $judul, string $pesan, string $tipe): void
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            self::kirim($admin->id, $judul, $pesan, $tipe);
        }
    }
}
