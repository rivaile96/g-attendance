<?php

namespace App\Http\Controllers;

use App\Models\OvertimeEvent;
use App\Models\OvertimeLog;
use App\Models\Division; // <-- 1. TAMBAHKAN INI
use App\Models\User;     // <-- 1. TAMBAHKAN INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class OvertimeLogController extends Controller
{
    /**
     * Menampilkan daftar event lembur yang tersedia & riwayat pengajuan lembur.
     */
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today('Asia/Jakarta')->toDateString();

        // ▼▼▼ BAGIAN QUERY INI YANG KITA UBAH TOTAL ▼▼▼
        $availableEvents = OvertimeEvent::where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->where(function ($query) use ($user) {
                // Kondisi 1: Event ditugaskan ke divisi si user
                $query->whereHas('assignments', function ($subQuery) use ($user) {
                    $subQuery->where('assignable_type', Division::class)
                             ->where('assignable_id', $user->division_id);
                });
                // ATAU Kondisi 2: Event ditugaskan langsung ke si user
                $query->orWhereHas('assignments', function ($subQuery) use ($user) {
                    $subQuery->where('assignable_type', User::class)
                             ->where('assignable_id', $user->id);
                });
            })
            ->latest()
            ->get();
        // ▲▲▲ ----------------------------------------- ▲▲▲
        
        // Ambil ID dari semua event yang sudah pernah di-klaim oleh user
        $claimedEventIds = OvertimeLog::where('user_id', $user->id)
                                    ->pluck('overtime_event_id')
                                    ->unique();

        // Query $overtimeLogs tetap sama
        $overtimeLogs = OvertimeLog::where('user_id', $user->id)
                                   ->with('overtimeEvent')
                                   ->latest()
                                   ->paginate(10);

        return view('overtime_logs.index', compact('availableEvents', 'overtimeLogs', 'claimedEventIds'));
    }

    /**
     * Menampilkan form untuk mengajukan klaim lembur berdasarkan event tertentu.
     * (Tidak ada perubahan di sini)
     */
    public function create(OvertimeEvent $event)
    {
        $user = Auth::user();
        $today = Carbon::today('Asia/Jakarta')->toDateString();

        // Cek otorisasi, apakah user boleh akses event ini
        $isAssignedToDivision = $event->assignments()
                                ->where('assignable_type', Division::class)
                                ->where('assignable_id', $user->division_id)
                                ->exists();

        $isAssignedToUser = $event->assignments()
                             ->where('assignable_type', User::class)
                             ->where('assignable_id', $user->id)
                             ->exists();

        if (
            !(\Carbon\Carbon::parse($event->start_date)->lte($today) && \Carbon\Carbon::parse($event->end_date)->gte($today)) ||
            !($isAssignedToDivision || $isAssignedToUser)
        ) {
            abort(403, 'Anda tidak ditugaskan untuk event lembur ini atau event sudah tidak aktif.');
        }
            
        return view('overtime_logs.create', compact('event'));
    }

    /**
     * Menyimpan klaim lembur baru ke database dengan validasi ketat.
     * (Tidak ada perubahan di sini)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'overtime_event_id' => 'required|exists:overtime_events,id',
            'date' => 'required|date|date_format:Y-m-d',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'notes' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $event = OvertimeEvent::findOrFail($validated['overtime_event_id']);
        
        $alreadyClaimed = OvertimeLog::where('user_id', $user->id)
            ->where('overtime_event_id', $event->id)
            ->exists();

        if ($alreadyClaimed) {
            return back()->with('error', 'Anda sudah pernah mengajukan klaim untuk event ini.');
        }

        if ($validated['date'] != now()->toDateString()) {
            return back()->with('error', 'Tanggal pengajuan harus hari ini.');
        }
        
        if ($validated['start_time'] != \Carbon\Carbon::parse($event->start_time)->format('H:i')) {
            return back()->with('error', 'Jam mulai tidak sesuai dengan jadwal event.');
        }

        $checkInDateTime = Carbon::parse($validated['date'] . ' ' . $validated['start_time'], 'Asia/Jakarta');
        $checkOutDateTime = Carbon::parse($validated['date'] . ' ' . $validated['end_time'], 'Asia/Jakarta');

        OvertimeLog::create([
            'user_id' => $user->id,
            'overtime_event_id' => $validated['overtime_event_id'],
            'start_time' => $checkInDateTime,
            'end_time' => $checkOutDateTime,
            'notes' => $validated['notes'],
            'status' => 'Pending',
        ]);

        return redirect()->route('overtime.index')->with('success', 'Klaim lembur berhasil diajukan.');
    }
}