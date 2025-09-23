<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    // Menampilkan halaman utama manajemen hari libur
    public function index()
    {
        $holidays = Holiday::orderBy('date', 'desc')->paginate(10);
        return view('admin.holidays.index', compact('holidays'));
    }

    // Menyimpan hari libur baru
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|unique:holidays,date',
            'description' => 'required|string|max:255',
        ]);

        Holiday::create($request->all());

        return back()->with('success', 'Hari libur berhasil ditambahkan.');
    }

    // Menghapus hari libur
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return back()->with('success', 'Hari libur berhasil dihapus.');
    }
};