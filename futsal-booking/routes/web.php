<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminLapanganController;
use App\Http\Controllers\Admin\AdminTarifController;
use App\Http\Controllers\Admin\AdminHariLiburController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminPembayaranController;
use App\Http\Controllers\Admin\AdminLaporanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===== CUSTOMER ROUTES (Penyewa) =====
Route::middleware('auth')->prefix('customer')->name('customer.')->group(function () {
    // Booking
    Route::resource('booking', BookingController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
});

// ===== ADMIN ROUTES =====
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('lapangan', AdminLapanganController::class);
    Route::resource('tarif', AdminTarifController::class);
    Route::resource('hari-libur', AdminHariLiburController::class);
    
    Route::get('/booking', [AdminBookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/{booking}', [AdminBookingController::class, 'show'])->name('booking.show');
    Route::patch('/booking/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('booking.update-status');
    
    Route::get('/pembayaran', [AdminPembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran/{pembayaran}', [AdminPembayaranController::class, 'show'])->name('pembayaran.show');
    Route::patch('/pembayaran/{pembayaran}/verify', [AdminPembayaranController::class, 'verify'])->name('pembayaran.verify');
    
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan/generate', [AdminLaporanController::class, 'generate'])->name('laporan.generate');
});

require __DIR__.'/auth.php'; 