<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Menampilkan daftar semua lokasi.
     */
    public function index()
    {
        $locations = Location::latest()->paginate(10);
        // Nanti kita akan buat view-nya di 'resources/views/admin/locations/index.blade.php'
        return view('admin.locations.index', compact('locations'));
    }

    /**
     * Menampilkan form untuk membuat lokasi baru.
     */
    public function create()
    {
        // Nanti kita akan buat view-nya di 'resources/views/admin/locations/create.blade.php'
        return view('admin.locations.create');
    }

    /**
     * Menyimpan lokasi baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'ip_address' => 'nullable|ip',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|integer|min:1',
        ]);

        Location::create($request->all());

        return redirect()->route('admin.locations.index')
                         ->with('success', 'Lokasi baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit lokasi.
     */
    public function edit(Location $location)
    {
        // Nanti kita akan buat view-nya di 'resources/views/admin/locations/edit.blade.php'
        return view('admin.locations.edit', compact('location'));
    }

    /**
     * Mengupdate data lokasi di database.
     */
    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'ip_address' => 'nullable|ip',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|integer|min:1',
        ]);

        $location->update($request->all());

        return redirect()->route('admin.locations.index')
                         ->with('success', 'Data lokasi berhasil diperbarui.');
    }

    /**
     * Menghapus lokasi dari database.
     */
    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('admin.locations.index')
                         ->with('success', 'Lokasi berhasil dihapus.');
    }
}