<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lapangan_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal_main');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->unsignedBigInteger('total_harga')->default(0);
            $table->enum('status_booking', ['pending', 'dp_dibayar', 'lunas', 'expired', 'batal'])->default('pending');
            $table->dateTime('payment_deadline')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};