<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lapangans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lapangan');
            $table->text('alamat')->nullable();
            $table->unsignedInteger('harga_per_jam')->nullable();
            $table->string('ukuran')->nullable();
            $table->unsignedInteger('kapasitas')->nullable();
            $table->json('fasilitas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lapangans');
    }
};