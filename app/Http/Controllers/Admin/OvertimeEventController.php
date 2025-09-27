<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OvertimeEvent;
use App\Models\Division; // <-- Tambahan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OvertimeEventController extends Controller
{
    public function index()
    {
        $overtimeEvents = OvertimeEvent::with('assignments.assignable')->latest()->paginate(10);
        return view('admin.overtime_events.index', compact('overtimeEvents'));
    }

    public function create()
    {
        $divisions = Division::orderBy('name')->get();
        return view('admin.overtime_events.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'division_ids' => 'required|array', // Pastikan minimal satu divisi dipilih
            'division_ids.*' => 'exists:divisions,id',
        ]);

        $overtimeEvent = OvertimeEvent::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'created_by' => Auth::id(),
        ]);

        // Simpan data divisi yang ditugaskan ke tabel pivot
        foreach ($validated['division_ids'] as $divisionId) {
            $division = Division::find($divisionId);
            $overtimeEvent->assignments()->create([
                'assignable_id' => $division->id,
                'assignable_type' => Division::class,
            ]);
        }

        return redirect()->route('admin.overtime-events.index')->with('success', 'Event lembur berhasil dibuat.');
    }
}