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
        'metode_pembayaran',
        'midtrans_order_id',
        'midtrans_snap_token',
        'midtrans_transaction_id',
        'midtrans_transaction_status',
        'midtrans_payment_type',
        'midtrans_payload',
    ];

    protected function casts(): array
    {
        return [
            'midtrans_payload' => 'array',
        ];
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
