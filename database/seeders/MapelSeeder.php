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
                "kepanjangan" => 'Mulok PGRI',
                "kependekan" => 'MP',
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
                "kepanjangan" => 'Teknologi Perkantoran',
                "kependekan" => 'TP',
            ],
            [
                "kepanjangan" => 'Komputer Akuntansi',
                "kependekan" => 'AK',
            ],
            [
                "kepanjangan" => 'Pengelola Kearsipan',
                "kependekan" => 'PK',
            ],
            [
                "kepanjangan" => 'Teknologi Jaringan Kabell & Nirkabel',
                "kependekan" => 'TJKN',
            ],
            [
                "kepanjangan" => 'Pemrograman Web & Perangkat Bergerak',
                "kependekan" => 'PWPB',
            ],
            [
                "kepanjangan" => 'Perkembangan Teknologi Akuntansi',
                "kependekan" => 'PTA',
            ],
            [
                "kepanjangan" => 'Adm Logistik/Pengelolaan Keuangan',
                "kependekan" => 'ALPK',
            ],
            [
                "kepanjangan" => 'Keamanan Jaringan',
                "kependekan" => 'KJ',
            ],
            [
                "kepanjangan" => 'Basis Data',
                "kependekan" => 'BD',
            ],
            [
                "kepanjangan" => 'Administrasi Pajak',
                "kependekan" => 'AP',
            ],
            [
                "kepanjangan" => 'Pengelolaan Humas dan Keprotokolan',
                "kependekan" => 'PHDK',
            ],
            [
                "kepanjangan" => 'Admin Online',
                "kependekan" => 'AO',
            ],
            [
                "kepanjangan" => 'Praktikum Akuntansi Jasa',
                "kependekan" => 'PAJ',
            ],
            [
                "kepanjangan" => 'Dagang dan Pengelolaan Sapras',
                "kependekan" => 'DPS',
            ],
            [
                "kepanjangan" => 'Cloud Computing',
                "kependekan" => 'CC',
            ],
            [
                "kepanjangan" => 'Pemprograman Beroreantasi Objek',
                "kependekan" => 'PBO',
            ],
            [
                "kepanjangan" => 'Ekonomi Bisnis & Adm. Umum',
                "kependekan" => 'EBAU',
            ],
            [
                "kepanjangan" => 'Pengelolaan Adm Umum',
                "kependekan" => 'PAU',
            ],
            [
                "kepanjangan" => 'Rancang Bangun Jaringan',
                "kependekan" => 'RBJ',
            ],
            [
                "kepanjangan" => 'Akuntansi Pemerintah',
                "kependekan" => 'AP',
            ],
            [
                "kepanjangan" => 'Rapat / Pertemuan',
                "kependekan" => 'RP',
            ],
            [
                "kepanjangan" => 'Pengelolaan Keuangan Sederhana',
                "kependekan" => 'PKS',
            ],
            [
                "kepanjangan" => 'Pengelolaan SDM',
                "kependekan" => 'PSDM',
            ],
        ];

        foreach ($mapels as $mapel) {
            Mapel::create($mapel);
        }
    }
}
