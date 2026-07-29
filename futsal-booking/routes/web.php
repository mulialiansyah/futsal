<?php

use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminHariLiburController;
use App\Http\Controllers\Admin\AdminLapanganController;
use App\Http\Controllers\Admin\AdminLaporanController;
use App\Http\Controllers\Admin\AdminPembayaranController;
use App\Http\Controllers\Admin\AdminTarifController;
use App\Http\Controllers\Admin\KetersediaanController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Customer\LapanganController as CustomerLapanganController;
use App\Http\Controllers\Customer\NotifikasiController;
use App\Http\Controllers\Customer\PembayaranController as CustomerPembayaranController;
use App\Http\Controllers\MidtransNotificationController;
use App\Http\Controllers\ProfileController;
use App\Models\Lapangan;
use App\Models\Tarif;
use Illuminate\Support\Facades\Route;

// ===== PUBLIC ROUTES =====
Route::get('/', function () {
    $lapangans = Lapangan::all();
    $tarifs = Tarif::all();

    return view('welcome', compact('lapangans', 'tarifs'));
});

Route::view('/syarat-ketentuan', 'syarat-ketentuan')->name('syarat-ketentuan');
Route::view('/kebijakan-privasi', 'kebijakan-privasi')->name('kebijakan-privasi');

// ===== BROWSE LAPANGAN (Publik, tanpa login) =====
Route::prefix('lapangan')->name('customer.lapangan.')->group(function () {
    Route::get('/', [CustomerLapanganController::class, 'index'])->name('index');
    Route::get('/denah', [CustomerLapanganController::class, 'denah'])->name('denah');
    Route::get('/{lapangan}', [CustomerLapanganController::class, 'show'])->name('show');
    Route::get('/{lapangan}/slots', [CustomerLapanganController::class, 'slots'])->name('slots');
});

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

// ===== CUSTOMER ROUTES (Penyewa) =====
Route::middleware('auth')->prefix('customer')->name('customer.')->group(function () {
    // Booking
    Route::get('booking/check-slots', [BookingController::class, 'checkSlots'])->name('booking.check-slots');
    Route::post('booking/{booking}/choose-refund', [BookingController::class, 'chooseRefund'])->name('booking.choose-refund');
    Route::post('booking/{booking}/request-cancel-refund', [BookingController::class, 'requestCancelRefund'])->name('booking.request-cancel-refund');
    Route::get('booking/{booking}/reschedule', [BookingController::class, 'rescheduleForm'])->name('booking.reschedule-form');
    Route::post('booking/{booking}/reschedule', [BookingController::class, 'processReschedule'])->name('booking.process-reschedule');
    Route::resource('booking', BookingController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
    Route::get('booking/{booking}/berhasil', [BookingController::class, 'success'])->name('booking.success');
    Route::get('booking/{booking}/download-dp', [BookingController::class, 'downloadDpReceipt'])->name('booking.download-dp');

    // Pembayaran customer
    Route::get('booking/{booking}/bayar', [CustomerPembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('booking/{booking}/bayar', [CustomerPembayaranController::class, 'store'])->name('pembayaran.store');
    Route::post('booking/{booking}/midtrans', [CustomerPembayaranController::class, 'createMidtransTransaction'])->name('pembayaran.midtrans');

    // Notifikasi
    Route::post('notifikasi/baca-semua', [NotifikasiController::class, 'markAllRead'])->name('notifikasi.readAll');
    Route::post('notifikasi/{notifikasi}/baca', [NotifikasiController::class, 'markRead'])->name('notifikasi.read');
});

Route::post('midtrans/notification', MidtransNotificationController::class)->name('midtrans.notification');

// ===== ADMIN ROUTES (Hanya Akses Admin Resmi) =====
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Master Data Resources
    Route::resource('lapangan', AdminLapanganController::class);
    Route::resource('tarif', AdminTarifController::class);
    Route::resource('hari-libur', AdminHariLiburController::class);

    // Kelola Booking
    Route::get('booking', [AdminBookingController::class, 'index'])->name('booking.index');
    Route::get('booking/{booking}', [AdminBookingController::class, 'show'])->name('booking.show');
    Route::patch('booking/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('booking.cancel');
    Route::post('booking/{booking}/confirm-refund', [AdminBookingController::class, 'confirmRefund'])->name('booking.confirm-refund');
    Route::post('booking/{booking}/refund', [AdminBookingController::class, 'storeRefund'])->name('booking.refund.store');

    // Kelola Pembayaran
    Route::get('pembayaran', [AdminPembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('pembayaran/{pembayaran}', [AdminPembayaranController::class, 'show'])->name('pembayaran.show');
    Route::patch('pembayaran/{pembayaran}/verify', [AdminPembayaranController::class, 'verify'])->name('pembayaran.verify');
    Route::post('booking/{booking}/confirm-cash', [AdminPembayaranController::class, 'confirmCash'])->name('pembayaran.confirm-cash');
    // Laporan
    Route::get('laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');

    // Kelola Ketersediaan Lapangan
    Route::get('ketersediaan', [KetersediaanController::class, 'index'])->name('ketersediaan.index');
    Route::post('ketersediaan', [KetersediaanController::class, 'store'])->name('ketersediaan.store');
    Route::delete('ketersediaan/{ketersediaan}', [KetersediaanController::class, 'destroy'])->name('ketersediaan.destroy');

    // Notifikasi Admin
    Route::post('notifikasi/baca-semua', [NotifikasiController::class, 'markAllRead'])->name('notifikasi.readAll');
    Route::post('notifikasi/{notifikasi}/baca', [NotifikasiController::class, 'markRead'])->name('notifikasi.read');
});

require __DIR__.'/auth.php';

// External cron service route (for Infinity Free - no cron support)
Route::get('/artisan/schedule', function () {
    if (request('token') !== env('CRON_SECRET')) {
        abort(403, 'Invalid token');
    }
    \Illuminate\Support\Facades\Artisan::call('schedule:run');
    return 'Schedule run completed';
});

// Temporary route for manual storage:link setup (remove after use)
Route::get('/artisan/storage-link', function () {
    if (request('token') !== env('CRON_SECRET')) {
        abort(403, 'Invalid token');
    }
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    return 'Storage link created. Remove this route after use.';
});
