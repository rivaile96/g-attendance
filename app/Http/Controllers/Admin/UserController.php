<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Division;
use App\Models\Shift; // <-- 1. DITAMBAHKAN
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua karyawan dengan filter.
     */
    public function index(Request $request)
    {
        $query = User::with('division', 'employmentData', 'shift')->latest();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }

        if ($request->filled('join_date')) {
            $query->whereHas('employmentData', function ($q) use ($request) {
                $q->whereDate('join_date', $request->join_date);
            });
        }

        $users = $query->paginate(10)->withQueryString();
        $divisions = Division::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'divisions'));
    }

    /**
     * Menampilkan form untuk membuat karyawan baru.
     */
    public function create()
    {
        $divisions = Division::orderBy('name')->get();
        $shifts = Shift::orderBy('name')->get(); // <-- 2. Mengambil data shift
        return view('admin.users.create', compact('divisions', 'shifts')); // <-- 2. Mengirim data shift ke view
    }

    /**
     * Menyimpan data karyawan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'division_id' => ['required', 'exists:divisions,id'],
            'shift_id' => ['nullable', 'exists:shifts,id'], // <-- 3. Validasi untuk shift_id
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'birth_date' => ['nullable', 'date'],
            'join_date' => ['nullable', 'date'],
            'employment_status' => ['nullable', 'string', 'max:255'],
        ]);

        $path = null;
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'division_id' => $request->division_id,
            'shift_id' => $request->shift_id, // <-- 3. Menyimpan shift_id
            'profile_photo_path' => $path,
        ]);

        $user->profile()->create([
            'birth_date' => $request->birth_date,
        ]);
        
        $user->employmentData()->create([
            'join_date' => $request->join_date,
            'employment_status' => $request->employment_status,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Karyawan baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data karyawan.
     */
    public function edit(User $user)
    {
        $user->load('profile', 'employmentData');
        $divisions = Division::orderBy('name')->get();
        $shifts = Shift::orderBy('name')->get(); // <-- 4. Mengambil data shift
        return view('admin.users.edit', compact('user', 'divisions', 'shifts')); // <-- 4. Mengirim data shift ke view
    }

    /**
     * Mengupdate data karyawan di database.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class.',email,'.$user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'division_id' => ['required', 'exists:divisions,id'],
            'shift_id' => ['nullable', 'exists:shifts,id'], // <-- 5. Validasi untuk shift_id
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'birth_date' => ['nullable', 'date'],
            'join_date' => ['nullable', 'date'],
            'employment_status' => ['nullable', 'string', 'max:255'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->division_id = $request->division_id;
        $user->shift_id = $request->shift_id; // <-- 5. Menyimpan shift_id

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $user->profile_photo_path = $request->file('profile_photo')->store('profile-photos', 'public');
        }
        
        $user->save();

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['birth_date' => $request->birth_date]
        );
        
        $user->employmentData()->updateOrCreate(
            ['user_id' => $user->id],
            ['join_date' => $request->join_date, 'employment_status' => $request->employment_status]
        );

        return redirect()->route('admin.users.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Menghapus data karyawan.
     */
    public function destroy(User $user)
    {
        if (Auth::id() == $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }
        
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}
