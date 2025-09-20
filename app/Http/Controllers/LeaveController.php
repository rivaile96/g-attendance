<?php

namespace App\Http\Controllers;

use App\Models\Leave; // <-- INI DIA SOLUSINYA
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LeaveController extends Controller
{
    /**
     * Menampilkan halaman riwayat pengajuan cuti & izin milik user.
     */
    public function index()
    {
        $leaves = Leave::where('user_id', Auth::id())
                       ->latest()
                       ->paginate(10);

        return view('leaves.index', compact('leaves'));
    }

    /**
     * Menampilkan form untuk membuat pengajuan cuti baru.
     */
    public function create()
    {
        return view('leaves.create');
    }

    /**
     * Menyimpan pengajuan cuti baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'in:Cuti Tahunan,Sakit,Izin Khusus'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'max:1000'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('leave_attachments', 'public');
        }

        Leave::create([
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'attachment_path' => $attachmentPath,
            'status' => 'Pending',
        ]);

        return redirect()->route('leaves.index')->with('success', 'Pengajuan cuti/izin Anda berhasil dikirim.');
    }
}