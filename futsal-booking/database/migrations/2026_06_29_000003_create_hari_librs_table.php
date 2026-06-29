<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hari_librs', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->unique();
            $table->string('keterangan')->nullable();
            $table->string('tipe')->default('nasional'); // nasional | cuti_bersama
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hari_librs');
    }
};
