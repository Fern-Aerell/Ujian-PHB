<?php

namespace Database\Seeders;

use App\Models\GuruMapelKelasKategoriKelas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GuruMapelKelasKategoriKelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guruMapelKelasKategoriKelasData = [
            [
                'guru_id' => 1,
                'mapel_id' => 11,
                'kelas_kategori_id' => 8,
                'kelas_id' => 3,
            ],
            [
                'guru_id' => 2,
                'mapel_id' => 9,
                'kelas_kategori_id' => 8,
                'kelas_id' => 3,
            ],
        ];

        foreach($guruMapelKelasKategoriKelasData as $guruMapelKelasKategoriKelas)
        {
            GuruMapelKelasKategoriKelas::create($guruMapelKelasKategoriKelas);
        }
    }
}
