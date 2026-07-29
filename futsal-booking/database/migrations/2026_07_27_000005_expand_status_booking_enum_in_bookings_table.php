<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Expands the status_booking enum to include closure decision statuses.
     * Uses raw SQL for MySQL because Laravel's Blueprint doesn't support in-place enum expansion cleanly.
     */
    public function up(): void
    {
        // Only run on MySQL/MariaDB; SQLite handles this via the create migration above.
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE bookings MODIFY COLUMN status_booking ENUM('pending','dp_dibayar','lunas','expired','batal','menunggu_keputusan_customer','menunggu_refund') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE bookings MODIFY COLUMN status_booking ENUM('pending','dp_dibayar','lunas','expired','batal') NOT NULL DEFAULT 'pending'");
        }
    }
};
