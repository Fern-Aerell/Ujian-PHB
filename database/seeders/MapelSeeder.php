<?php

namespace Database\Seeders;

use App\Models\Mapel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mapels = [
            [
                "kepanjangan" => 'Agama dan Budi Pakerti',
                "kependekan" => 'AGAMA',
            ],
            [
                "kepanjangan" => 'Pendidikan Kewarganegaraan',
                "kependekan" => 'PKN',
            ],
            [
                "kepanjangan" => 'Bahasa Indonesia',
                "kependekan" => 'B.INDO',
            ],
            [
                "kepanjangan" => 'Matematika',
                "kependekan" => 'MTK',
            ],
            [
                "kepanjangan" => 'Bahasa Inggris',
                "kependekan" => 'B.ING',
            ],
            [
                "kepanjangan" => 'Pendidikan Jasmani, Olahraga, dan Kesehatan',
                "kependekan" => 'PJOK',
            ],
            [
                "kepanjangan" => 'Publik Speaking',
                "kependekan" => 'PS',
            ],
            [
                "kepanjangan" => 'Produk Kreatif dan Kewirausahaan',
                "kependekan" => 'KWU',
            ],
            [
                "kepanjangan" => 'Pemrograman Web & Perangkat Bergerak',
                "kependekan" => 'PWPB',
            ],
            [
                "kepanjangan" => 'Basis Data',
                "kependekan" => 'BD',
            ],
            [
                "kepanjangan" => 'Pemprograman Beroreantasi Objek',
                "kependekan" => 'PBO',
            ]
        ];

        foreach ($mapels as $mapel) {
            Mapel::create($mapel);
        }
    }
}
