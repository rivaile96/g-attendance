<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Baris yang menyebabkan error sudah dihapus.
        // Kita langsung buat datanya karena tabel dijamin kosong oleh `migrate:fresh`.

        Location::create([
            'name' => 'Kantor Pusat',
            'address' => 'Lokasi Tes Saat Ini',
            'ip_address' => '140.213.45.209', 
            'latitude' => -6.9206016,
            'longitude' => 107.610112,
            'radius' => 100,
        ]);
    }
}

