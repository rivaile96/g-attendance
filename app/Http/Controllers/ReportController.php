<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use App\Models\OvertimeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\OvertimeExport;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman Laporan Absensi.
     */
    public function attendances(Request $request)
    {
        $user = Auth::user();
        
        $query = Attendance::with('user.division', 'location')->orderBy('check_in', 'desc');

        // Terapkan filter tanggal untuk semua role
        if ($request->filled('start_date')) {
            $query->whereDate('check_in', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('check_in', '<=', $request->end_date);
        }

        // Terapkan filter spesifik berdasarkan role
        if (!$user->is_admin) {
            $query->where('user_id', $user->id);
        } else {
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
        }
        
        $attendances = $query->paginate(20)->withQueryString();
        $users = $user->is_admin ? User::orderBy('name')->get() : [];

        return view('reports.attendances', compact('attendances', 'users'));
    }

    /**
     * Method untuk men-download laporan absensi dalam format PDF atau Excel.
     */
    public function exportAttendances(Request $request)
    {
        $user = Auth::user();
        $type = $request->query('type', 'pdf');
        
        $query = Attendance::with('user.division', 'location')->orderBy('check_in', 'desc');

        // Terapkan filter tanggal untuk semua role
        if ($request->filled('start_date')) {
            $query->whereDate('check_in', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('check_in', '<=', $request->end_date);
        }

        // Terapkan filter spesifik berdasarkan role
        if (!$user->is_admin) {
            $query->where('user_id', $user->id);
        } else {
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
        }
        
        $attendances = $query->get();
        $filename = 'laporan-absensi-' . now()->format('d-m-Y') . '.' . $type;

        if ($type == 'xlsx') {
            return Excel::download(new AttendanceExport($attendances), $filename);
        } else {
            $pdf = Pdf::loadView('reports.attendances_pdf', compact('attendances'));
            return $pdf->download($filename);
        }
    }

    /**
     * Menampilkan halaman Laporan Lembur dengan filter.
     */
    public function overtimes(Request $request)
    {
        $user = Auth::user();
        
        $query = OvertimeLog::with(['user.division', 'overtimeEvent'])
                           ->where('status', 'Approved');

        // Terapkan filter tanggal untuk semua role
        if ($request->filled('start_date')) {
            $query->whereDate('start_time', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('start_time', '<=', $request->end_date);
        }

        // Terapkan filter spesifik berdasarkan role
        if (!$user->is_admin) {
            $query->where('user_id', $user->id);
        } else {
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
        }
        
        $overtimeLogs = $query->latest()->paginate(20)->withQueryString();
        $users = $user->is_admin ? User::orderBy('name')->get() : [];

        return view('reports.overtimes', compact('overtimeLogs', 'users'));
    }

    /**
     * Export Laporan Lembur (PDF atau Excel).
     */
    public function exportOvertimes(Request $request)
    {
        $user = Auth::user();
        $type = $request->query('type', 'pdf');

        $query = OvertimeLog::with(['user.division', 'overtimeEvent'])
                           ->where('status', 'Approved');

        // Terapkan filter tanggal untuk semua role
        if ($request->filled('start_date')) {
            $query->whereDate('start_time', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('start_time', '<=', $request->end_date);
        }

        // Terapkan filter spesifik berdasarkan role
        if (!$user->is_admin) {
            $query->where('user_id', $user->id);
        } else {
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
        }

        $overtimeLogs = $query->latest()->get();
        $filename = 'laporan-lembur-' . now()->format('d-m-Y') . '.' . $type;

        if ($type === 'xlsx') {
            return Excel::download(new OvertimeExport($overtimeLogs), $filename);
        } else {
            $pdf = Pdf::loadView('reports.overtimes_pdf', compact('overtimeLogs'));
            return $pdf->download($filename);
        }
    }
}
