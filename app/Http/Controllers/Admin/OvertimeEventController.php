<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OvertimeEvent;
use App\Models\Division;
use App\Models\User; // 1. Import model User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OvertimeEventController extends Controller
{
    /**
     * Menampilkan daftar event lembur.
     * (Tidak ada perubahan di sini)
     */
    public function index()
    {
        $overtimeEvents = OvertimeEvent::with('assignments.assignable')->latest()->paginate(10);
        return view('admin.overtime_events.index', compact('overtimeEvents'));
    }

    /**
     * Menampilkan form untuk membuat event baru.
     * (Diubah untuk mengirim data user juga)
     */
    public function create()
    {
        $divisions = Division::orderBy('name')->get();
        $users = User::orderBy('name')->get(); // 2. Ambil data semua user

        return view('admin.overtime_events.create', compact('divisions', 'users')); // 3. Kirim data user ke view
    }

    /**
     * Menyimpan event lembur baru dengan logika penugasan dinamis.
     * (Logika di sini diubah total)
     */
    public function store(Request $request)
    {
        // 4. Validasi input baru yang dinamis
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'assignment_type' => 'required|in:division,user', // Validasi tipe penugasan
            'division_ids' => 'required_if:assignment_type,division|array', // Wajib jika tipenya divisi
            'division_ids.*' => 'exists:divisions,id',
            'user_ids' => 'required_if:assignment_type,user|array', // Wajib jika tipenya user
            'user_ids.*' => 'exists:users,id',
        ]);

        // 5. Buat Event Lembur (sama seperti sebelumnya)
        $overtimeEvent = OvertimeEvent::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'created_by' => Auth::id(),
        ]);

        // 6. Logika penyimpanan penugasan yang dinamis
        if ($request->assignment_type === 'division') {
            foreach ($validated['division_ids'] as $divisionId) {
                $overtimeEvent->assignments()->create([
                    'assignable_id' => $divisionId,
                    'assignable_type' => Division::class, // Simpan sebagai tipe Division
                ]);
            }
        } elseif ($request->assignment_type === 'user') {
            foreach ($validated['user_ids'] as $userId) {
                $overtimeEvent->assignments()->create([
                    'assignable_id' => $userId,
                    'assignable_type' => User::class, // Simpan sebagai tipe User
                ]);
            }
        }

        return redirect()->route('admin.overtime-events.index')->with('success', 'Event lembur berhasil dibuat.');
    }
}