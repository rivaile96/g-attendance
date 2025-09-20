<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today('Asia/Jakarta');

        // Data untuk Kartu Statistik (KPI Cards)
        $totalEmployees = User::count();
        $presentToday = Attendance::whereDate('check_in', $today)->count();

        // 🔥 update: pakai kolom 'status' langsung
        $lateToday = Attendance::whereDate('check_in', $today)
                               ->where('status', 'Late') // hitung hanya yang terlambat
                               ->count();

        $absentToday = $totalEmployees - $presentToday;

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
        ]);
    }
}
