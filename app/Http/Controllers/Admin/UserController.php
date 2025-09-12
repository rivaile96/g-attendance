<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua karyawan dengan filter.
     */
    public function index(Request $request)
    {
        // Memulai query dasar dengan relasi yang dibutuhkan (eager loading)
        $query = User::with('division', 'employmentData')->latest();

        // Terapkan filter jika ada input dari user
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

        // Ambil hasil query dengan paginasi, dan pertahankan filter saat ganti halaman
        $users = $query->paginate(5)->withQueryString();
        
        // Ambil semua divisi untuk ditampilkan di dropdown filter
        $divisions = Division::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'divisions'));
    }

    /**
     * Menampilkan form untuk membuat karyawan baru.
     */
    public function create()
    {
        $divisions = Division::orderBy('name')->get();
        return view('admin.users.create', compact('divisions'));
    }

    /**
     * Menyimpan data karyawan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            // Validasi untuk tabel 'users'
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'division_id' => ['required', 'exists:divisions,id'],
            'profile_photo' => ['nullable', 'image', 'max:2048'], // Maksimal 2MB

            // Validasi untuk data tambahan
            'birth_date' => ['nullable', 'date'],
            'join_date' => ['nullable', 'date'],
            'employment_status' => ['nullable', 'string', 'max:255'],
        ]);

        $path = null;
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        // 1. Buat data user utama
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'division_id' => $request->division_id,
            'profile_photo_path' => $path,
        ]);

        // 2. Buat data profil yang terhubung dengan user baru
        $user->profile()->create([
            'birth_date' => $request->birth_date,
            // Tambahkan field lain dari 'user_profiles' di sini jika sudah ada di form
        ]);
        
        // 3. Buat data pekerjaan yang terhubung dengan user baru
        $user->employmentData()->create([
            'join_date' => $request->join_date,
            'employment_status' => $request->employment_status,
             // Tambahkan field lain dari 'user_employment_data' di sini jika sudah ada di form
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Karyawan baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data karyawan.
     */
    public function edit(User $user)
    {
        // Eager load relasi agar tidak terjadi N+1 query di view
        $user->load('profile', 'employmentData');
        $divisions = Division::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'divisions'));
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
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'birth_date' => ['nullable', 'date'],
            'join_date' => ['nullable', 'date'],
            'employment_status' => ['nullable', 'string', 'max:255'],
        ]);

        // 1. Update data di tabel 'users'
        $user->name = $request->name;
        $user->email = $request->email;
        $user->division_id = $request->division_id;

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

        // 2. Update atau buat data di 'user_profiles'
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['birth_date' => $request->birth_date]
        );
        
        // 3. Update atau buat data di 'user_employment_data'
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
        if (auth()->id() == $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }
        
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->delete(); // Karena ada 'cascade', data profile & employment akan ikut terhapus.
        return redirect()->route('admin.users.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}

