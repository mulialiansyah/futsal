<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'lapangan_id',
        'tanggal_main',
        'jam_mulai',
        'jam_selesai',
        'total_harga',
        'status_booking',
        'payment_deadline',
        'expired_at',
    ];

    protected $casts = [
        'tanggal_main' => 'date',
        'payment_deadline' => 'datetime',
        'expired_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    // ===== ACCESSORS - AUTO-RELEASE LOGIC =====

    /**
     * Accessor: Hitung sisa waktu pembayaran (dalam detik)
     * Akses via: $booking->sisa_waktu
     */
    public function getSisaWaktuAttribute(): ?int
    {
        if ($this->status_booking !== 'pending' || !$this->payment_deadline) {
            return null;
        }

        $sekarang = Carbon::now();
        $sisa = $this->payment_deadline->diffInSeconds($sekarang, false);

        return max(0, $sisa);
    }

    /**
     * Accessor: Format sisa waktu jadi HH:MM:SS
     * Akses via: $booking->sisa_waktu_format
     */
    public function getSisaWaktuFormatAttribute(): string
    {
        $sisa = $this->sisa_waktu;

        if ($sisa === null || $sisa <= 0) {
            return '00:00:00';
        }

        $jam = intdiv($sisa, 3600);
        $menit = intdiv($sisa % 3600, 60);
        $detik = $sisa % 60;

        return sprintf('%02d:%02d:%02d', $jam, $menit, $detik);
    }

    // ===== METHODS =====

    /**
     * Cek apakah booking sudah expired
     */
    public function isExpired(): bool
    {
        return $this->status_booking === 'pending' 
            && $this->payment_deadline 
            && Carbon::now()->greaterThan($this->payment_deadline);
    }

    /**
     * Mark booking sebagai expired (slot di-release)
     */
    public function markAsExpired(): void
    {
        $this->update([
            'status_booking' => 'expired',
            'expired_at' => Carbon::now(),
        ]);
    }
}