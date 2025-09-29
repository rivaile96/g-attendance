<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OvertimeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OvertimeApprovalController extends Controller
{
    /**
     * Menampilkan daftar semua klaim lembur yang diajukan oleh karyawan.
     */
    public function index()
    {
        // Ambil semua log lembur, urutkan dari yang terbaru.
        // Gunakan 'with' untuk mengambil data relasi (user & event) secara efisien (menghindari N+1 problem).
        $overtimeLogs = OvertimeLog::with(['user.division', 'overtimeEvent'])
                                   ->latest()
                                   ->paginate(15);

        // Tampilkan view yang akan kita buat selanjutnya
        return view('admin.overtime_approvals.index', compact('overtimeLogs'));
    }

    /**
     * Mengupdate status klaim lembur (Approve/Reject).
     */
    public function update(Request $request, OvertimeLog $log)
    {
        // Validasi input, pastikan status yang dikirim valid.
        $validated = $request->validate([
            'status' => 'required|in:Approved,Rejected',
        ]);

        // Update status klaim lembur yang dipilih
        $log->status = $validated['status'];
        $log->approved_by = Auth::id(); // Catat siapa admin yang memproses
        $log->save();

        // Kirim notifikasi (jika ada, bisa ditambahkan nanti)
        // $log->user->notify(new OvertimeProcessedNotification($log));

        // Redirect kembali ke halaman daftar dengan pesan sukses.
        return back()->with('success', 'Status klaim lembur berhasil diperbarui.');
    }
}