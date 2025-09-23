<?php

use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LeaveController as AdminLeaveController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CalendarController;
use Illuminate\Support\Facades\Route;

// ... (Rute publik & rute user biasa biarkan sama) ...

// --- RUTE PUBLIK ---
Route::get('/', function () {
    return view('welcome');
});

// --- RUTE UNTUK SEMUA USER YANG SUDAH LOGIN ---
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // ... (Rute Absensi, Cuti Karyawan, Laporan, Profil tetap sama)
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');

    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');

    Route::get('/reports/attendances', [ReportController::class, 'attendances'])->name('reports.attendances');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/calendar-events', [CalendarController::class, 'getEvents'])->name('calendar.events');
});


// --- RUTE KHUSUS UNTUK ADMIN ---
// ▼▼▼ PERUBAHAN UTAMA ADA DI SINI ▼▼▼
Route::middleware(['auth', 'verified', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('locations', LocationController::class);
    Route::resource('users', UserController::class);
    Route::resource('shifts', ShiftController::class);

    // Rute untuk manajemen hari libur
    Route::get('/holidays', [HolidayController::class, 'index'])->name('holidays.index');
    Route::post('/holidays', [HolidayController::class, 'store'])->name('holidays.store');
    Route::delete('/holidays/{holiday}', [HolidayController::class, 'destroy'])->name('holidays.destroy');

    // Rute untuk manajemen persetujuan cuti oleh admin
    // Namanya kita ubah menjadi admin.leaves.index agar tidak bentrok
    Route::get('/leaves', [AdminLeaveController::class, 'index'])->name('leaves.index'); 
    Route::put('/leaves/{leave}', [AdminLeaveController::class, 'update'])->name('leaves.update');
});
// ▲▲▲ ------------------------------ ▲▲▲


// Mengimpor semua rute untuk autentikasi
require __DIR__.'/auth.php';