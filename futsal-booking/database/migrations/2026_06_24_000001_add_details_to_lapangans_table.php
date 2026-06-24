<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lapangans', function (Blueprint $table) {
            $table->text('alamat')->nullable()->after('nama_lapangan');
            $table->unsignedInteger('harga_per_jam')->nullable()->after('alamat');
            $table->string('ukuran')->nullable()->after('harga_per_jam');
            $table->unsignedInteger('kapasitas')->nullable()->after('ukuran');
            $table->json('fasilitas')->nullable()->after('kapasitas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lapangans', function (Blueprint $table) {
            $table->dropColumn(['alamat', 'harga_per_jam', 'ukuran', 'kapasitas', 'fasilitas']);
        });
    }
};
