<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the divisions with their names and color classes
        $divisions = [
            'Executive' => 'bg-gray-200 text-gray-800',
            'Finance' => 'bg-green-100 text-green-800',
            'Human Resources' => 'bg-pink-100 text-pink-800',
            'Information Technology (IT)' => 'bg-blue-100 text-blue-800',
            'Marketing' => 'bg-purple-100 text-purple-800',
            'Operations' => 'bg-indigo-100 text-indigo-800',
            'Sales' => 'bg-yellow-100 text-yellow-800',
            'Support' => 'bg-orange-100 text-orange-800',
            'Logistic' => 'bg-teal-100 text-teal-800',
            'General Affairs' => 'bg-cyan-100 text-cyan-800',
        ];

        // Loop through and create each division
        foreach ($divisions as $name => $class) {
            // Use firstOrCreate to prevent duplicates
            // It will find a division with the given name,
            // or create it if it doesn't exist, assigning the color class.
            Division::firstOrCreate(
                ['name' => $name],
                ['color_class' => $class]
            );
        }
    }
}