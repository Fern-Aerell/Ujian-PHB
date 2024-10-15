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
                "romawi" => 'X',
                "pengucapan" => 'Sepuluh'
            ],
            [
                "bilangan" => 11,
                "romawi" => 'XI',
                "pengucapan" => 'Sebelas'
            ],
            [
                "bilangan" => 12,
                "romawi" => 'XII',
                "pengucapan" => 'Dua Belas'
            ],
        ];

        foreach($kelass as $kelas)
        {
            Kelas::create($kelas);
        }
    }
}
