<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ===== AUTO-RELEASE EXPIRED BOOKINGS =====
// Jalankan setiap menit untuk cek booking pending yang sudah lewat deadline
Schedule::command('bookings:release-expired')
    ->everyMinute()
    ->withoutOverlapping();
