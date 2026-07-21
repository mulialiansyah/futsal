<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->string('metode_pembayaran')->nullable()->after('bukti_transfer');
            $table->string('midtrans_order_id')->nullable()->unique()->after('metode_pembayaran');
            $table->text('midtrans_snap_token')->nullable()->after('midtrans_order_id');
            $table->string('midtrans_transaction_id')->nullable()->after('midtrans_snap_token');
            $table->string('midtrans_transaction_status')->nullable()->after('midtrans_transaction_id');
            $table->string('midtrans_payment_type')->nullable()->after('midtrans_transaction_status');
            $table->json('midtrans_payload')->nullable()->after('midtrans_payment_type');
        });
    }

    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropUnique(['midtrans_order_id']);
            $table->dropColumn([
                'metode_pembayaran',
                'midtrans_order_id',
                'midtrans_snap_token',
                'midtrans_transaction_id',
                'midtrans_transaction_status',
                'midtrans_payment_type',
                'midtrans_payload',
            ]);
        });
    }
};
