<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Notifications\LeaveRequestProcessed; // <-- 1. Pastikan ini di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::with('user.division')
                       ->latest()
                       ->paginate(15);
        
        return view('admin.leaves.index', compact('leaves'));
    }

    public function update(Request $request, Leave $leave)
    {
        $request->validate([
            'status' => ['required', 'in:Approved,Rejected'],
            'rejection_reason' => ['required_if:status,Rejected', 'nullable', 'string'],
        ]);

        $leave->status = $request->status;
        $leave->rejection_reason = $request->rejection_reason;
        $leave->approved_by = Auth::id();
        $leave->save();

        // ▼▼▼ 2. TAMBAHKAN BARIS INI UNTUK MENGIRIM NOTIFIKASI ▼▼▼
        $leave->user->notify(new LeaveRequestProcessed($leave));

        return redirect()->route('admin.leaves.index')->with('success', 'Status pengajuan berhasil diperbarui dan notifikasi telah dikirim.');
    }
}