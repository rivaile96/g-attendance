<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * Menampilkan daftar semua shift.
     */
    public function index()
    {
        $shifts = Shift::latest()->paginate(10);
        return view('admin.shifts.index', compact('shifts'));
    }

    /**
     * Menampilkan form untuk membuat shift baru.
     */
    public function create()
    {
        return view('admin.shifts.create');
    }

    /**
     * Menyimpan shift baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:shifts',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        Shift::create($request->all());

        return redirect()->route('admin.shifts.index')
                         ->with('success', 'Jadwal kerja baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit shift.
     */
    public function edit(Shift $shift)
    {
        return view('admin.shifts.edit', compact('shift'));
    }

    /**
     * Mengupdate data shift di database.
     */
    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:shifts,name,' . $shift->id,
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $shift->update($request->all());

        return redirect()->route('admin.shifts.index')
                         ->with('success', 'Jadwal kerja berhasil diperbarui.');
    }

    /**
     * Menghapus shift dari database.
     */
    public function destroy(Shift $shift)
    {
        // Opsional: Cek dulu apakah ada user yang masih menggunakan shift ini
        if ($shift->users()->count() > 0) {
            return back()->with('error', 'Jadwal kerja tidak bisa dihapus karena masih digunakan oleh karyawan.');
        }
        
        $shift->delete();

        return redirect()->route('admin.shifts.index')
                         ->with('success', 'Jadwal kerja berhasil dihapus.');
    }
}