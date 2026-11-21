<?php

use App\Http\Controllers\Admin\ArsipPdfController as AdminArsipPdfController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KopLaporanController as AdminKopLaporanController;
use App\Http\Controllers\Admin\PegawaiController as AdminPegawaiController;
use App\Http\Controllers\Admin\VerifikasiLaporanController as AdminVerifikasiLaporanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Staff\CetakLaporanController as StaffCetakLaporanController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\LaporanKinerjaController as StaffLaporanKinerjaController;
use Illuminate\Support\Facades\Route;

// Redirect Home to Login / Dashboard
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('staff.dashboard');
    }

    return redirect()->route('login');
});

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Staff Routes
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');

    // Laporan Kinerja
    Route::get('/laporan', [StaffLaporanKinerjaController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/create', [StaffLaporanKinerjaController::class, 'create'])->name('laporan.create');
    Route::post('/laporan', [StaffLaporanKinerjaController::class, 'store'])->name('laporan.store');
    Route::get('/laporan/{laporan}', [StaffLaporanKinerjaController::class, 'show'])->name('laporan.show');
    Route::get('/laporan/{laporan}/edit', [StaffLaporanKinerjaController::class, 'edit'])->name('laporan.edit');
    Route::put('/laporan/{laporan}', [StaffLaporanKinerjaController::class, 'update'])->name('laporan.update');
    Route::delete('/laporan/{laporan}', [StaffLaporanKinerjaController::class, 'destroy'])->name('laporan.destroy');

    // Cetak & PDF
    Route::get('/cetak', [StaffCetakLaporanController::class, 'index'])->name('cetak.index');
    Route::get('/cetak/preview', [StaffCetakLaporanController::class, 'preview'])->name('cetak.preview');
    Route::get('/cetak/download', [StaffCetakLaporanController::class, 'download'])->name('cetak.download');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Manajemen Pegawai
    Route::get('/pegawai', [AdminPegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('/pegawai/create', [AdminPegawaiController::class, 'create'])->name('pegawai.create');
    Route::post('/pegawai', [AdminPegawaiController::class, 'store'])->name('pegawai.store');
    Route::get('/pegawai/{pegawai}/edit', [AdminPegawaiController::class, 'edit'])->name('pegawai.edit');
    Route::put('/pegawai/{pegawai}', [AdminPegawaiController::class, 'update'])->name('pegawai.update');
    Route::post('/pegawai/{pegawai}/reset-password', [AdminPegawaiController::class, 'resetPassword'])->name('pegawai.reset-password');
    Route::post('/pegawai/{pegawai}/toggle-status', [AdminPegawaiController::class, 'toggleStatus'])->name('pegawai.toggle-status');
    Route::delete('/pegawai/{pegawai}', [AdminPegawaiController::class, 'destroy'])->name('pegawai.destroy');

    // Template & Kop Laporan
    Route::get('/kop', [AdminKopLaporanController::class, 'edit'])->name('kop.edit');
    Route::put('/kop', [AdminKopLaporanController::class, 'update'])->name('kop.update');

    // Verifikasi & Monitoring Kinerja
    Route::get('/verifikasi', [AdminVerifikasiLaporanController::class, 'index'])->name('verifikasi.index');
    Route::post('/verifikasi/bulk-approve', [AdminVerifikasiLaporanController::class, 'bulkApprove'])->name('verifikasi.bulk-approve');
    Route::get('/verifikasi/{laporan}', [AdminVerifikasiLaporanController::class, 'show'])->name('verifikasi.show');
    Route::post('/verifikasi/{laporan}', [AdminVerifikasiLaporanController::class, 'updateVerifikasi'])->name('verifikasi.update');

    // Arsip PDF
    Route::get('/arsip', [AdminArsipPdfController::class, 'index'])->name('arsip.index');
    Route::get('/arsip/{arsip}/preview', [AdminArsipPdfController::class, 'preview'])->name('arsip.preview');
    Route::get('/arsip/{arsip}/download', [AdminArsipPdfController::class, 'download'])->name('arsip.download');
    Route::post('/arsip/generate-manual', [AdminArsipPdfController::class, 'generateManual'])->name('arsip.generate-manual');
    Route::delete('/arsip/{arsip}', [AdminArsipPdfController::class, 'destroy'])->name('arsip.destroy');
});
