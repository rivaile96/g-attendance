<?php

namespace App\Http\Controllers;

use App\Models\OvertimeEvent;
use App\Models\OvertimeLog;
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

        // Query $availableEvents tetap sama
        $availableEvents = OvertimeEvent::where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->whereHas('assignments', function ($query) use ($user) {
                $query->where('assignable_type', 'App\Models\Division')
                      ->where('assignable_id', $user->division_id);
            })
            ->latest()
            ->get();
        
        // Ambil ID dari semua event yang sudah pernah di-klaim oleh user
        $claimedEventIds = OvertimeLog::where('user_id', $user->id)
                                    ->pluck('overtime_event_id')
                                    ->unique();

        // Query $overtimeLogs tetap sama
        $overtimeLogs = OvertimeLog::where('user_id', $user->id)
                                   ->with('overtimeEvent')
                                   ->latest()
                                   ->paginate(10);

        // Tambahkan $claimedEventIds ke compact()
        return view('overtime_logs.index', compact('availableEvents', 'overtimeLogs', 'claimedEventIds'));
    }

    /**
     * Menampilkan form untuk mengajukan klaim lembur berdasarkan event tertentu.
     */
    public function create(OvertimeEvent $event)
    {
        $user = Auth::user();
        $today = Carbon::today('Asia/Jakarta')->toDateString();

        if (
            !($event->start_date->lte($today) && $event->end_date->gte($today)) ||
            !$event->assignments()->where('assignable_type', 'App\Models\Division')->where('assignable_id', $user->division_id)->exists()
        ) {
            abort(403, 'Anda tidak ditugaskan untuk event lembur ini atau event sudah tidak aktif.');
        }
            
        return view('overtime_logs.create', compact('event'));
    }

    /**
     * Menyimpan klaim lembur baru ke database dengan validasi ketat.
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
        
        // 1. Cek apakah user sudah pernah klaim untuk event ini
        $alreadyClaimed = OvertimeLog::where('user_id', $user->id)
            ->where('overtime_event_id', $event->id)
            ->exists();

        if ($alreadyClaimed) {
            return back()->with('error', 'Anda sudah pernah mengajukan klaim untuk event ini.');
        }

        // 2. Cek apakah tanggal yang disubmit sesuai dengan hari ini
        if ($validated['date'] != now()->toDateString()) {
            return back()->with('error', 'Tanggal pengajuan harus hari ini.');
        }
        
        // 3. Cek apakah jam mulai yang disubmit sesuai dengan jam event
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