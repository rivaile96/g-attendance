<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    /**
     * Menampilkan daftar semua pengajuan cuti untuk diproses admin.
     */
    public function index()
    {
        // Ambil semua data, eager load relasi user, urutkan dari yang terbaru
        $leaves = Leave::with('user.division')
                       ->latest()
                       ->paginate(15);

        return view('admin.leaves.index', compact('leaves'));
    }

    /**
     * Mengupdate status pengajuan (Approve/Reject).
     */
    public function update(Request $request, Leave $leave)
    {
        $request->validate([
            'status' => ['required', 'in:Approved,Rejected'],
            // Alasan wajib diisi jika statusnya 'Rejected'
            'rejection_reason' => ['required_if:status,Rejected', 'nullable', 'string'],
        ]);

        $leave->status = $request->status;
        $leave->rejection_reason = $request->rejection_reason;
        $leave->approved_by = Auth::id(); // Catat siapa admin yang memproses
        $leave->save();

        // Nanti di sini kita bisa tambahkan notifikasi ke user.

        return redirect()->route('admin.leaves.index')->with('success', 'Status pengajuan berhasil diperbarui.');
    }
}