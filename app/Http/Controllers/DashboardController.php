<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Holiday; // ✅ suntikan model Holiday
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today('Asia/Jakarta');

        // ✅ cek apakah hari ini libur
        $isHoliday = Holiday::where('date', $today)->exists();

        // Data untuk Kartu Statistik (KPI Cards)
        $totalEmployees = User::count();
        $presentToday = Attendance::whereDate('check_in', $today)->count();

        // 🔥 update: pakai kolom 'status' langsung
        $lateToday = Attendance::whereDate('check_in', $today)
                               ->where('status', 'Late') // hitung hanya yang terlambat
                               ->count();

        // ✅ Logika Baru: kalau hari ini libur, anggap semua hadir (absent = 0)
        $absentToday = $isHoliday ? 0 : $totalEmployees - $presentToday;

        // Data untuk Grafik Absensi Mingguan
        $chartData = [];
        $chartLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today('Asia/Jakarta')->subDays($i);
            $dayName = $date->translatedFormat('D');
            $dateString = $date->toDateString();
            $attendanceCount = Attendance::whereDate('check_in', $dateString)->count();
            $chartLabels[] = $dayName;
            $chartData[] = $attendanceCount;
        }

        // Kita hanya mengirim data yang dibutuhkan, tanpa layout
        return view('dashboard', [
            'totalEmployees' => $totalEmployees,
            'presentToday'   => $presentToday,
            'lateToday'      => $lateToday,
            'absentToday'    => $absentToday,
            'chartLabels'    => $chartLabels,
            'chartData'      => $chartData,
            'isHoliday'      => $isHoliday, // ✅ opsional: bisa dipakai di view
        ]);
    }
}
