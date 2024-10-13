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
                "tags" => json_encode(['10', '11', '12', 'X', 'XI', 'XII']),
            ],
            [
                "kepanjangan" => 'Pendidikan Kewarganegaraan',
                "kependekan" => 'PKN',
                "tags" => json_encode(['10', '11', '12', 'X', 'XI', 'XII']),
            ],
            [
                "kepanjangan" => 'Bahasa Indonesia',
                "kependekan" => 'B.INDO',
                "tags" => json_encode(['10', '11', '12', 'X', 'XI', 'XII']),
            ],
            [
                "kepanjangan" => 'Matematika',
                "kependekan" => 'MTK',
                "tags" => json_encode(['10', '11', '12', 'X', 'XI', 'XII']),
            ],
            [
                "kepanjangan" => 'Bahasa Inggris',
                "kependekan" => 'B.ING',
                "tags" => json_encode(['10', '11', '12', 'X', 'XI', 'XII']),
            ],
            [
                "kepanjangan" => 'Pendidikan Jasmani, Olahraga, dan Kesehatan',
                "kependekan" => 'PJOK',
                "tags" => json_encode(['10', '11', '12', 'X', 'XI', 'XII']),
            ],
            [
                "kepanjangan" => 'Publik Speaking',
                "kependekan" => 'PS',
                "tags" => json_encode(['11', '12', 'XI', 'XII']),
            ],
            [
                "kepanjangan" => 'Produk Kreatif dan Kewirausahaan',
                "kependekan" => 'KWU',
                "tags" => json_encode(['11', '12', 'XI', 'XII', 'PPLG', 'DKV', 'TKJ']),
            ],
            [
                "kepanjangan" => 'Pemrograman Web & Perangkat Bergerak',
                "kependekan" => 'PWPB',
                "tags" => json_encode(['11', '12', 'XI', 'XII', 'PPLG']),
            ],
            [
                "kepanjangan" => 'Basis Data',
                "kependekan" => 'BD',
                "tags" => json_encode(['11', '12', 'XI', 'XII', 'PPLG']),
            ],
            [
                "kepanjangan" => 'Pemprograman Beroreantasi Objek',
                "kependekan" => 'PBO',
                "tags" => json_encode(['11', '12', 'XI', 'XII', 'PPLG']),
            ]
        ];

        foreach ($mapels as $mapel) {
            Mapel::create($mapel);
        }
    }
}
