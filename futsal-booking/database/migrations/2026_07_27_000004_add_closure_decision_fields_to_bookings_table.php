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
        Schema::table('bookings', function (Blueprint $table) {
            $table->timestamp('opsi_deadline')->nullable()->after('expired_at');
            $table->text('alasan_penutupan')->nullable()->after('opsi_deadline');
            $table->string('original_status')->nullable()->after('alasan_penutupan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['opsi_deadline', 'alasan_penutupan', 'original_status']);
        });
    }
};
