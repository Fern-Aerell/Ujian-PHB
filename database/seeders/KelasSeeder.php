<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelass = [
            [
                "bilangan" => 10,
                "romawi" => 'X'
            ],
            [
                "bilangan" => 11,
                "romawi" => 'XI'
            ],
            [
                "bilangan" => 12,
                "romawi" => 'XII'
            ],
        ];

        foreach($kelass as $kelas)
        {
            Kelas::create($kelas);
        }
    }
}
