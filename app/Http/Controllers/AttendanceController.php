<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $todayAttendance = Attendance::where('user_id', Auth::id())
            ->whereDate('check_in', Carbon::today('Asia/Jakarta'))
            ->first();

        return view('attendance.index', compact('todayAttendance'));
    }

    public function checkIn(Request $request)
    {
        // 1. Validasi request
        $request->validate([
            'type' => 'required|in:wifi,gps',
            'latitude' => 'required_if:type,gps|numeric',
            'longitude' => 'required_if:type,gps|numeric',
        ]);

        $user = Auth::user();
        $type = $request->input('type');
        $now = now('Asia/Jakarta');

        // 2. Cek apakah user sudah absen masuk hari ini
        $alreadyCheckedIn = Attendance::where('user_id', $user->id)
            ->whereDate('check_in', $now)->exists();

        if ($alreadyCheckedIn) {
            return back()->with('error', 'Anda sudah melakukan absen masuk hari ini.');
        }

        // --- 3. LOGIKA VALIDASI DINAMIS ---
        $locationId = null;
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        if ($type == 'wifi') {
            // Cek apakah IP user cocok dengan SALAH SATU lokasi yang terdaftar
            $location = Location::where('ip_address', $request->ip())->first();

            if (!$location) {
                return back()->with('error', 'Absensi gagal. Anda tidak terhubung ke jaringan WiFi kantor yang valid.');
            }
            $locationId = $location->id;

        } elseif ($type == 'gps') {
            $locations = Location::all();
            if ($locations->isEmpty()) {
                return back()->with('error', 'Absensi GPS gagal. Tidak ada lokasi yang terdaftar di sistem.');
            }

            $nearestLocation = null;
            $shortestDistance = -1;

            // Cari lokasi terdekat dari semua pilihan yang ada
            foreach ($locations as $location) {
                $distance = $this->calculateDistance($latitude, $longitude, $location->latitude, $location->longitude);
                if ($shortestDistance == -1 || $distance < $shortestDistance) {
                    $shortestDistance = $distance;
                    $nearestLocation = $location;
                }
            }
            
            // Validasi dengan lokasi terdekat
            if ($nearestLocation && $shortestDistance <= $nearestLocation->radius) {
                $locationId = $nearestLocation->id;
            } else {
                return back()->with('error', 'Absensi gagal. Jarak Anda ('. round($shortestDistance) .' meter) terlalu jauh dari lokasi absensi terdekat.');
            }
        }

        // --- 4. Simpan Data Absensi Jika Validasi Berhasil ---
        Attendance::create([
            'user_id' => $user->id,
            'location_id' => $locationId,
            'check_in' => $now,
            'check_in_latitude' => $latitude,
            'check_in_longitude' => $longitude,
            'check_in_type' => $type,
        ]);

        return back()->with('success', 'Berhasil melakukan absen masuk!');
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('check_in', Carbon::today('Asia/Jakarta'))
            ->whereNull('check_out')
            ->first();

        if (!$attendance) {
            return back()->with('error', 'Anda tidak bisa check-out karena belum melakukan check-in atau sudah check-out sebelumnya.');
        }

        $attendance->update(['check_out' => now('Asia/Jakarta')]);

        return back()->with('success', 'Berhasil melakukan absen pulang. Selamat beristirahat!');
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;
        $latFrom = deg2rad($lat1); $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2); $lonTo = deg2rad($lon2);
        $latDelta = $latTo - $latFrom; $lonDelta = $lonTo - $lonFrom;
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
}