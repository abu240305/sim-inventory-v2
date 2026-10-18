<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;


// Auth Routes
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Dashboard as home page
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // User CRUD routes
    Route::resource('user', UserController::class)->middleware('check.permission:user.index');

    // Role & Menu Management
    Route::resource('role', \App\Http\Controllers\RoleController::class)->middleware('check.permission:role.index');
    Route::resource('menu', \App\Http\Controllers\MenuController::class)->middleware('check.permission:menu.index');
    Route::get('permission', [\App\Http\Controllers\PermissionController::class, 'index'])->name('permission.index')->middleware('check.permission:permission.index');
    Route::put('permission', [\App\Http\Controllers\PermissionController::class, 'update'])->name('permission.update')->middleware('check.permission:permission.index');



    // Activity Log
    Route::get('activity-log', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::get('activity-log/data', [\App\Http\Controllers\ActivityLogController::class, 'getData'])->name('activity-log.data');
    Route::get('activity-log/statistics', [\App\Http\Controllers\ActivityLogController::class, 'statistics'])->name('activity-log.statistics');

    // Website Settings
    Route::get('settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index')->middleware('check.permission:settings.index');
    Route::post('settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update')->middleware('check.permission:settings.index');
    Route::get('settings/clear-cache', [\App\Http\Controllers\SettingController::class, 'clearCache'])->name('settings.clear-cache')->middleware('check.permission:settings.index');

    // Personal Profile
    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::post('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Impersonate Features
    Route::get('impersonate/start/{id}', [\App\Http\Controllers\ImpersonateController::class, 'start'])->name('impersonate.start');
    Route::get('impersonate/stop', [\App\Http\Controllers\ImpersonateController::class, 'stop'])->name('impersonate.stop');

    // System Status & Backup
    Route::get('system/health', [\App\Http\Controllers\SystemController::class, 'health'])->name('system.health')->middleware('check.permission:system.health');
    Route::get('system/backup', [\App\Http\Controllers\SystemController::class, 'backup'])->name('system.backup')->middleware('check.permission:system.health');

    // SIM Inventaris Routes (Phase 1)
    Route::resource('kategori', \App\Http\Controllers\KategoriController::class);
    Route::resource('barang', \App\Http\Controllers\BarangController::class);
    Route::resource('transaksi-masuk', \App\Http\Controllers\TransaksiMasukController::class);
    Route::get('transaksi-keluar/{id}/cetak-form', [\App\Http\Controllers\TransaksiKeluarController::class, 'cetakForm'])->name('transaksi-keluar.cetak-form');
    Route::post('transaksi-keluar/{id}/retur', [\App\Http\Controllers\TransaksiKeluarController::class, 'retur'])->name('transaksi-keluar.retur');
    Route::resource('transaksi-keluar', \App\Http\Controllers\TransaksiKeluarController::class);
    
    // SIM Inventaris - Modul Peminjaman & Pengembalian Alat (Dokumen 2)
    Route::get('peminjaman/{id}/cetak-berita-acara', [\App\Http\Controllers\PeminjamanController::class, 'cetakBeritaAcara'])->name('peminjaman.cetak-berita-acara');
    Route::post('peminjaman/{id}/kembalikan', [\App\Http\Controllers\PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    Route::resource('peminjaman', \App\Http\Controllers\PeminjamanController::class);
    
    // SIM Inventaris Routes (Phase 2 - Laporan)
    Route::get('laporan', [\App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export/pdf', [\App\Http\Controllers\LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
    Route::get('laporan/export/excel', [\App\Http\Controllers\LaporanController::class, 'exportExcel'])->name('laporan.export.excel');
});

