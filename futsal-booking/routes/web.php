<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminLapanganController; // <-- Pakai controller utama admin lapangan
use App\Http\Controllers\Admin\AdminTarifController;
use App\Http\Controllers\Admin\AdminHariLiburController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminPembayaranController;
use App\Http\Controllers\Admin\AdminLaporanController;
use App\Http\Controllers\Admin\KetersediaanController;
use Illuminate\Support\Facades\Route;

// ===== PUBLIC ROUTES =====
Route::get('/', function () {
    $lapangans = \App\Models\Lapangan::all();
    return view('welcome', compact('lapangans'));
});

// ===== DASHBOARD REDIRECTOR =====
Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('customer.booking.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// ===== USER PROFILE ROUTES =====
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

use App\Http\Controllers\Customer\PembayaranController as CustomerPembayaranController;
use App\Http\Controllers\Customer\LapanganController as CustomerLapanganController;

// ===== CUSTOMER ROUTES (Penyewa) =====
Route::middleware('auth')->prefix('customer')->name('customer.')->group(function () {
    // Browse & Detail Lapangan
    Route::get('lapangan', [CustomerLapanganController::class, 'index'])->name('lapangan.index');
    Route::get('lapangan/{lapangan}', [CustomerLapanganController::class, 'show'])->name('lapangan.show');
    Route::get('lapangan/{lapangan}/slots', [CustomerLapanganController::class, 'slots'])->name('lapangan.slots');

    // Booking
    Route::resource('booking', BookingController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
    
    // Pembayaran customer
    Route::get('booking/{booking}/bayar', [CustomerPembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('booking/{booking}/bayar', [CustomerPembayaranController::class, 'store'])->name('pembayaran.store');
});

// ===== ADMIN ROUTES (Hanya Akses Admin Resmi) =====
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Master Data Resources
    Route::resource('lapangan', AdminLapanganController::class);
    Route::resource('tarif', AdminTarifController::class);
    Route::resource('hari-libur', AdminHariLiburController::class);
    
    // Kelola Booking
    Route::get('/booking', [AdminBookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/{booking}', [AdminBookingController::class, 'show'])->name('booking.show');
    Route::patch('/booking/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('booking.update-status');
    
    // Kelola Pembayaran
    Route::get('/pembayaran', [AdminPembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran/{pembayaran}', [AdminPembayaranController::class, 'show'])->name('pembayaran.show');
    Route::patch('/pembayaran/{pembayaran}/verify', [AdminPembayaranController::class, 'verify'])->name('pembayaran.verify');
    Route::post('/booking/{booking}/confirm-cash', [AdminPembayaranController::class, 'confirmCash'])->name('pembayaran.confirm-cash');
    
    // Laporan
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');

    // Kelola Ketersediaan Lapangan
    Route::get('/ketersediaan', [KetersediaanController::class, 'index'])->name('ketersediaan.index');
    Route::post('/ketersediaan', [KetersediaanController::class, 'store'])->name('ketersediaan.store');
    Route::delete('/ketersediaan/{ketersediaan}', [KetersediaanController::class, 'destroy'])->name('ketersediaan.destroy');
});

require __DIR__.'/auth.php';