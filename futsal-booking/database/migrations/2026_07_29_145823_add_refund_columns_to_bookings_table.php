<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('nominal_refund', 15, 0)->nullable()->after('alasan_penutupan');
            $table->string('bukti_refund')->nullable()->after('nominal_refund');
            $table->text('catatan_refund')->nullable()->after('bukti_refund');
            $table->timestamp('tanggal_refund')->nullable()->after('catatan_refund');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'nominal_refund',
                'bukti_refund',
                'catatan_refund',
                'tanggal_refund',
            ]);
        });
    }
};
