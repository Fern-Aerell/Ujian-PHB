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
        $folder_logo_in_storage = __DIR__.'/../../storage/app/public/logo';
        $file_logo = __DIR__.'/../../resources/assets/logo/logo.png';

        if(!file_exists($folder_logo_in_storage)) {
            mkdir($folder_logo_in_storage, 0777, true);
        }

        if(file_exists($file_logo)) {
            copy($file_logo, __DIR__.'/../../storage/app/public/logo/logo.png');
        }

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
