<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // This command will execute the specified seeders in order.
        $this->call([
            DivisionSeeder::class,
            LocationSeeder::class,
        ]);
    }
}