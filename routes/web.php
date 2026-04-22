<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MidtransWebhookController;

// ============================================
// HALAMAN PUBLIK (tanpa login)
// ============================================

// Landing Page
Route::get('/', function () {
    try {
        $lapangans = \App\Models\Lapangan::all();
        $totalLapangan = $lapangans->count();
    } catch (\Throwable $e) {
        report($e);
        $lapangans = collect();
        $totalLapangan = 0;
    }

    return view('landing', compact('lapangans', 'totalLapangan'));
});

// Lapangan
Route::get('/lapangan', [LapanganController::class, 'index'])->name('lapangan.index');

// Booking (pengunjung bisa booking tanpa login)
Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/sukses/{booking}', [BookingController::class, 'sukses'])->name('booking.sukses');

// Pembayaran Midtrans (publik, tanpa login)
Route::post('/booking/payment/callback', [PaymentController::class, 'callback'])->name('booking.payment.callback');
Route::get('/booking/payment/{booking}', [PaymentController::class, 'pay'])->name('booking.payment');
Route::post('/midtrans/callback', [MidtransWebhookController::class, 'callback'])->name('midtrans.callback');

// ============================================
// LOGIN & LOGOUT
// ============================================

Route::redirect('/login', '/admin');
Route::get('/admin', [AuthController::class, 'showLogin'])->name('login');
Route::post('/admin', [AuthController::class, 'login'])->name('admin.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================
// HALAMAN ADMIN (harus login dulu)
// ============================================

Route::middleware('auth')->group(function () {

    // Dashboard admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Data Booking admin
    Route::get('/admin/booking', [BookingController::class, 'data'])->name('admin.booking');

    // Approve & Cancel booking
    Route::patch('/admin/booking/{booking}/approve', [BookingController::class, 'approve'])->name('admin.booking.approve');
    Route::patch('/admin/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('admin.booking.cancel');

    // Update status booking (fleksibel: pending/approved/cancelled)
    Route::post('/admin/booking/{booking}/status', [BookingController::class, 'updateStatus'])->name('admin.booking.status');

    // Export Report PDF
    Route::get('/admin/export/harian', [ExportController::class, 'harian'])->name('admin.export.harian');
    Route::get('/admin/export/mingguan', [ExportController::class, 'mingguan'])->name('admin.export.mingguan');
    Route::get('/admin/export/bulanan', [ExportController::class, 'bulanan'])->name('admin.export.bulanan');

    // CRUD Lapangan admin
    Route::get('/admin/lapangan', [LapanganController::class, 'adminIndex'])->name('admin.lapangan');
    Route::get('/admin/lapangan/create', [LapanganController::class, 'create'])->name('admin.lapangan.create');
    Route::post('/admin/lapangan', [LapanganController::class, 'store'])->name('admin.lapangan.store');
    Route::get('/admin/lapangan/{lapangan}/edit', [LapanganController::class, 'edit'])->name('admin.lapangan.edit');
    Route::put('/admin/lapangan/{lapangan}', [LapanganController::class, 'update'])->name('admin.lapangan.update');
    Route::delete('/admin/lapangan/{lapangan}', [LapanganController::class, 'destroy'])->name('admin.lapangan.destroy');
});