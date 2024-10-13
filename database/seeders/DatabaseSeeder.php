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
        $this->call([
            UserTypeSeeder::class,
            UserSeeder::class,
            ConfigSeeder::class,
            KelasSeeder::class,
            KelasKategoriSeeder::class,
            MapelSeeder::class,
        ]);
    }
}
