<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::orderBy('date', 'desc')->paginate(10);
        return view('admin.holidays.index', compact('holidays'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|unique:holidays,date',
            'description' => 'required|string|max:255',
        ]);

        $holiday = Holiday::create($validated);

        // Jika request datang dari JavaScript (AJAX), kirim jawaban JSON
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Hari libur berhasil ditambahkan.',
                'holiday' => $holiday
            ]);
        }

        // Jika request biasa, lakukan redirect
        return back()->with('success', 'Hari libur berhasil ditambahkan.');
    }

    public function destroy(Request $request, Holiday $holiday)
    {
        $holiday->delete();

        // Jika request datang dari JavaScript (AJAX), kirim jawaban JSON
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Hari libur berhasil dihapus.']);
        }

        // Jika request biasa (misal: form tanpa JS), lakukan redirect
        return back()->with('success', 'Hari libur berhasil dihapus.');
    }
}