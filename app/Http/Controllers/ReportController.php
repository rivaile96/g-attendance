<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman Laporan Absensi.
     */
    public function attendances(Request $request)
    {
        $user = Auth::user();
        
        $query = Attendance::with('user.division', 'location')->orderBy('check_in', 'desc');

        if (!$user->is_admin) {
            $query->where('user_id', $user->id);
        }

        if ($user->is_admin) {
            if ($request->filled('start_date')) {
                $query->whereDate('check_in', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('check_in', '<=', $request->end_date);
            }
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
        }
        
        $attendances = $query->paginate(20)->withQueryString();
        $users = $user->is_admin ? User::orderBy('name')->get() : [];

        return view('reports.attendances', compact('attendances', 'users'));
    }

    /**
     * Method untuk men-download laporan PDF.
     */
    public function downloadPdf(Request $request)
    {
        $user = Auth::user();
        
        $query = Attendance::with('user.division', 'location')->orderBy('check_in', 'desc');

        if (!$user->is_admin) {
            $query->where('user_id', $user->id);
        }

        if ($user->is_admin) {
            if ($request->filled('start_date')) {
                $query->whereDate('check_in', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('check_in', '<=', $request->end_date);
            }
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
        }
        
        $attendances = $query->get();
        
        $pdf = Pdf::loadView('reports.attendances_pdf', compact('attendances'));

        return $pdf->download('laporan-absensi-' . now()->format('d-m-Y') . '.pdf');
    }
}