<?php

namespace Database\Seeders;

use App\Models\Guru;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guruData = [
            [
                'user_id' => 4
            ],
            [
                'user_id' => 5
            ]
        ];
        foreach($guruData as $guru)
        {
            Guru::create($guru);
        }
    }
}
