<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\Admin\LeaveController as AdminLeaveController;
use App\Http\Controllers\Admin\OvertimeEventController; // <-- Tambahan
use App\Http\Controllers\OvertimeLogController; // <-- Tambahan

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- RUTE PUBLIK ---
Route::get('/', function () {
    return view('welcome');
});

// --- RUTE UNTUK SEMUA USER YANG SUDAH LOGIN ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/calendar-events', [CalendarController::class, 'getEvents'])->name('calendar.events');

    // Absensi
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');

    // Cuti & Izin
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
    
    // Lembur (BARU)
    Route::get('/overtime-logs', [OvertimeLogController::class, 'index'])->name('overtime.index');
    Route::get('/overtime-logs/{event}/create', [OvertimeLogController::class, 'create'])->name('overtime.create');
    Route::post('/overtime-logs', [OvertimeLogController::class, 'store'])->name('overtime.store');

    // Laporan Absensi
    Route::get('/reports/attendances', [ReportController::class, 'attendances'])->name('reports.attendances');
    Route::get('/reports/attendances/pdf', [ReportController::class, 'downloadPdf'])->name('reports.attendances.pdf');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- RUTE KHUSUS UNTUK ADMIN ---
Route::middleware(['auth', 'verified', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('shifts', ShiftController::class);
    Route::resource('locations', LocationController::class);
    
    // Event Lembur (BARU)
    Route::resource('overtime-events', OvertimeEventController::class);
    
    // Persetujuan Lembur
    Route::get('/overtime-approvals', [\App\Http\Controllers\Admin\OvertimeApprovalController::class, 'index'])->name('overtime-approvals.index');
    Route::put('/overtime-approvals/{log}', [\App\Http\Controllers\Admin\OvertimeApprovalController::class, 'update'])->name('overtime-approvals.update');
    
    // Persetujuan Cuti
    Route::get('/leaves', [AdminLeaveController::class, 'index'])->name('leaves.index');
    Route::put('/leaves/{leave}', [AdminLeaveController::class, 'update'])->name('leaves.update');
    
    // Hari Libur
    Route::get('/holidays', [HolidayController::class, 'index'])->name('holidays.index');
    Route::post('/holidays', [HolidayController::class, 'store'])->name('holidays.store');
    Route::delete('/holidays/{holiday}', [HolidayController::class, 'destroy'])->name('holidays.destroy');
});

require __DIR__.'/auth.php';

// --- RUTE TAMBAHAN LAPORAN LEMBUR ---
Route::get('/reports/overtimes', [ReportController::class, 'overtimes'])->name('reports.overtimes');
Route::get('/reports/overtimes/export', [ReportController::class, 'exportOvertimes'])->name('reports.overtimes.export');
