<?php

namespace Database\Seeders;

use App\Models\KelasKategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KelasKategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas_kategoris = [
            [
                "kepanjangan" => 'Akuntansi Keuangan Lembaga',
                "kependekan" => 'AKL',
            ],
            [
                "kepanjangan" => 'Desain Komunikasi Visual',
                "kependekan" => 'DKV',
            ],
            [
                "kepanjangan" => 'Otomatisasi Tata Kelola Perkantoran',
                "kependekan" => 'OTKP',
            ],
            [
                "kepanjangan" => 'Tekknik Komputer dan Jaringan',
                "kependekan" => 'TKJ',
            ],
            [
                "kepanjangan" => 'Usaha Perjalanan Wisata',
                "kependekan" => 'UPW',
            ],
            [
                "kepanjangan" => 'Perbankan Syariah',
                "kependekan" => 'PS',
            ],
            [
                "kepanjangan" => 'Bisnis Daring dan Pemasaran',
                "kependekan" => 'BDP',
            ],
            [
                "kepanjangan" => 'Pengembangan Perangkat Lunak dan Gim',
                "kependekan" => 'PPLG',
            ],
        ];

        foreach ($kelas_kategoris as $kelas_kategori) {
            KelasKategori::create($kelas_kategori);
        }   
    }
}
