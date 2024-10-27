<?php

namespace Database\Seeders;

use App\Models\Murid;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MuridSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $muridData = [
            [
                'user_id' => 2,
                'kelas_id' => 3,
                'kelas_kategori_id' => 8
            ],
            [
                'user_id' => 3,
                'kelas_id' => 3,
                'kelas_kategori_id' => 8
            ]
        ];
        foreach($muridData as $murid)
        {
            Murid::create($murid);
        }
    }
}
