<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarifs', function (Blueprint $table) {
            $table->id();
            $table->string('kategori');        // standar | internasional
            $table->string('tipe_hari');       // weekday | weekend
            $table->time('jam_mulai');         // 08:00
            $table->time('jam_selesai');       // 15:00
            $table->unsignedInteger('harga');  // harga per jam pada window ini
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarifs');
    }
};
