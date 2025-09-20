<?php

use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LeaveController as AdminLeaveController; // <-- Tambahan
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sinilah kamu bisa mendaftarkan rute web untuk aplikasi. Rute-rute
| ini dimuat oleh RouteServiceProvider dan semuanya akan
| ditetapkan ke grup middleware "web". Buat sesuatu yang hebat!
|
*/

// --- RUTE PUBLIK ---
Route::get('/', function () {
    return view('welcome');
});

// --- RUTE UNTUK SEMUA USER YANG SUDAH LOGIN ---
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Rute Fungsionalitas Absensi
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');

    // Rute Cuti & Izin untuk Karyawan
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');

    // Rute Laporan Absensi
    Route::get('/reports/attendances', [ReportController::class, 'attendances'])->name('reports.attendances');

    // Rute Manajemen Profil (Bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- RUTE KHUSUS UNTUK ADMIN ---
Route::middleware(['auth', 'verified', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('locations', LocationController::class);
    Route::resource('users', UserController::class);
    Route::resource('shifts', ShiftController::class);

    // Rute tambahan untuk manajemen cuti/izin oleh admin
    Route::get('/leaves', [AdminLeaveController::class, 'index'])->name('leaves.index');
    Route::put('/leaves/{leave}', [AdminLeaveController::class, 'update'])->name('leaves.update');
});

// Mengimpor semua rute untuk autentikasi
require __DIR__.'/auth.php';
