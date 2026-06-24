<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\LapanganController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Customer\PembayaranController as CustomerPembayaranController;

// Redirect root berdasarkan role
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('customer.home');
    }
    return redirect()->route('login');
});

// ─── ADMIN ROUTES ─────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Kelola lapangan
    Route::resource('lapangan', LapanganController::class);

    // Lihat semua booking
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.updateStatus');

    // Verifikasi pembayaran
    Route::get('/pembayaran', [AdminPembayaranController::class, 'index'])->name('pembayaran.index');
    Route::patch('/pembayaran/{pembayaran}/verifikasi', [AdminPembayaranController::class, 'verifikasi'])->name('pembayaran.verifikasi');
});

// ─── CUSTOMER ROUTES ──────────────────────────────────────────────────────────
Route::prefix('customer')->name('customer.')->middleware(['auth', 'customer'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Booking lapangan
    Route::get('/booking', [CustomerBookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/create', [CustomerBookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [CustomerBookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{booking}', [CustomerBookingController::class, 'show'])->name('booking.show');
    Route::delete('/booking/{booking}', [CustomerBookingController::class, 'destroy'])->name('booking.destroy');

    // Upload pembayaran DP
    Route::get('/pembayaran/{booking}/create', [CustomerPembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('/pembayaran/{booking}', [CustomerPembayaranController::class, 'store'])->name('pembayaran.store');
});

// ─── PROFILE ROUTES (Breeze default) ─────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
