<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'lapangan_id',
        'tanggal_main',
        'jam_mulai',
        'jam_selesai',
        'total_harga',
        'metode_pembayaran',
        'status_booking',
        'payment_deadline',
        'pelunasan_deadline',
        'expired_at',
    ];

    protected $casts = [
        'tanggal_main' => 'date',
        'payment_deadline' => 'datetime',
        'pelunasan_deadline' => 'datetime',
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

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }

    // ===== ACCESSORS =====

    public function getTotalDibayarAttribute(): int
    {
        if ($this->relationLoaded('pembayarans')) {
            return $this->pembayarans->where('status_verifikasi', 'diterima')->sum('nominal');
        }

        return $this->pembayarans()->where('status_verifikasi', 'diterima')->sum('nominal');
    }

    public function getSisaTagihanAttribute(): int
    {
        return max(0, $this->total_harga - $this->total_dibayar);
    }

    /**
     * Accessor: Hitung sisa waktu pembayaran DP pertama (dalam detik)
     */
    public function getSisaWaktuAttribute(): ?int
    {
        if ($this->status_booking !== 'pending' || ! $this->payment_deadline) {
            return null;
        }

        $sekarang = Carbon::now();
        $sisa = $this->payment_deadline->diffInSeconds($sekarang, false);

        return max(0, $sisa);
    }

    /**
     * Accessor: Hitung sisa waktu pelunasan (dalam detik)
     */
    public function getSisaWaktuPelunasanAttribute(): ?int
    {
        if ($this->status_booking !== 'dp_dibayar' || ! $this->pelunasan_deadline) {
            return null;
        }

        $sekarang = Carbon::now();
        $sisa = $this->pelunasan_deadline->diffInSeconds($sekarang, false);

        return max(0, $sisa);
    }

    /**
     * Accessor: Format sisa waktu DP jadi HH:MM:SS
     */
    public function getSisaWaktuFormatAttribute(): string
    {
        $sisa = $this->sisa_waktu;

        return $this->formatSisaWaktu($sisa);
    }

    /**
     * Accessor: Format sisa waktu pelunasan jadi HH:MM:SS
     */
    public function getSisaWaktuPelunasanFormatAttribute(): string
    {
        $sisa = $this->sisa_waktu_pelunasan;

        return $this->formatSisaWaktu($sisa);
    }

    private function formatSisaWaktu(?int $sisa): string
    {
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
     * Cek apakah booking DP sudah expired
     */
    public function isExpired(): bool
    {
        return $this->status_booking === 'pending'
            && $this->payment_deadline
            && Carbon::now()->greaterThan($this->payment_deadline);
    }

    /**
     * Cek apakah batas pelunasan sudah expired
     */
    public function isPelunasanExpired(): bool
    {
        return $this->status_booking === 'dp_dibayar'
            && $this->pelunasan_deadline
            && Carbon::now()->greaterThan($this->pelunasan_deadline);
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

    /**
     * Mark booking sebagai batal jika gagal pelunasan
     */
    public function markAsBatal(): void
    {
        $this->update([
            'status_booking' => 'batal',
            'expired_at' => Carbon::now(),
        ]);
    }
}
