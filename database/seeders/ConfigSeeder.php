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
        $folders = [
            __DIR__ . '/../../storage/app/public/logo',
            __DIR__ . '/../../storage/app/public/slider'
        ];

        $files = [
            [__DIR__ . '/../../resources/assets/logo/logo.webp', __DIR__ . '/../../storage/app/public/logo/logo.webp'],
            [__DIR__ . '/../../resources/assets/slider/slider1.webp', __DIR__ . '/../../storage/app/public/slider/slider1.webp'],
            [__DIR__ . '/../../resources/assets/slider/slider2.webp', __DIR__ . '/../../storage/app/public/slider/slider2.webp'],
            [__DIR__ . '/../../resources/assets/slider/slider3.webp', __DIR__ . '/../../storage/app/public/slider/slider3.webp'],
        ];

        foreach ($folders as $folder) {
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }
        }

        foreach ($files as $file) {
            if (file_exists($file[0])) {
                copy($file[0], $file[1]);
            }
        }

        $configData = [
            [
                'logo' => 'storage/logo/logo.webp',
                'school_name' => 'SMK PGRI PEKANBARU',
                'activity_type' => 'Ujian',
                'activity_title' => 'Penilaian Harian Bulanan',
                'activity_title_abbreviation' => 'PHB',
                'exam_date_start' => now()->format('Y-m-d'),
                'exam_date_end' => now()->addDays(7)->format('Y-m-d'),
                'holiday_date' => now()->addDays(rand(1, 3))->format('d') . ',' . now()->addDays(rand(4, 6))->format('d'),
                'exam_time_start' => '12:00',
                'exam_time_end' => '16:00',
                'slider_images' => json_encode([
                    'storage/slider/slider1.webp',
                    'storage/slider/slider2.webp',
                    'storage/slider/slider3.webp'
                ]),
            ]
        ];

        foreach ($configData as $config) {
            Config::create($config);
        }
    }
}
