<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configData = [
            [
                'logo' => 'storage/logo/logo.png',
                'school_name' => 'SMK PGRI PEKANBARU',
                'activity_type' => 'Ujian',
                'activity_title' => 'Penilaian Harian Bulanan',
                'activity_title_abbreviation' => 'PHB'
            ]
        ];

        foreach($configData as $config) {
            Config::create($config);
        }
    }
}
