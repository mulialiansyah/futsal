<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lapangans', function (Blueprint $table) {
            $table->string('kategori')->default('standar')->after('nama_lapangan');       // standar | internasional
            $table->string('jenis_lapangan')->nullable()->after('kategori');               // sintetis | vinyl
            $table->string('tipe_venue')->nullable()->after('jenis_lapangan');             // indoor | outdoor
        });
    }

    public function down(): void
    {
        Schema::table('lapangans', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'jenis_lapangan', 'tipe_venue']);
        });
    }
};
