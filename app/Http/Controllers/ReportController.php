<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function attendances(Request $request)
    {
        $user = Auth::user();
        
        // Memulai query dasar dengan relasi
        $query = Attendance::with('user.division', 'location')->latest();

        // Cek apakah user adalah admin (asumsi punya kolom 'is_admin' di tabel users)
        // Jika bukan admin, filter hanya untuk data miliknya sendiri
        if (!$user->is_admin) {
            $query->where('user_id', $user->id);
        }

        // --- Fitur Filter (hanya untuk admin) ---
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

        // Kirim data user (untuk filter admin) dan hasil query
        $users = $user->is_admin ? \App\Models\User::orderBy('name')->get() : [];

        return view('reports.attendances', compact('attendances', 'users'));
    }
}