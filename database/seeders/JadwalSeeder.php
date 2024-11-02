<?php

namespace Database\Seeders;

use App\Models\Config;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\KelasKategori;
use App\Models\Mapel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil konfigurasi
        $config = Config::first();

        if ($config) {
            // Dapatkan rentang tanggal dari konfigurasi
            $startDate = new \DateTime($config->exam_date_start); // Format: Y-m-d
            $endDate = new \DateTime($config->exam_date_end); // Format: Y-m-d

            // Menghitung selisih hari antara start dan end date
            $daysInterval = (int) $endDate->diff($startDate)->days;

            // Tentukan jumlah jadwal yang ingin dibuat
            $jumlahJadwal = 5;

            // Pastikan jumlah tidak melebihi selisih hari
            $jumlahJadwal = min($jumlahJadwal, $daysInterval + 1); // +1 untuk menyertakan tanggal akhir

            // Ambil daftar kelas, mapel, dan kategori
            $kelasIds = Kelas::pluck('id')->toArray();
            $mapelIds = Mapel::pluck('id')->toArray();
            $kelasKategoriIds = KelasKategori::pluck('id')->toArray();

            // Buat jadwal sebanyak $jumlahJadwal
            for ($i = 0; $i < $jumlahJadwal; $i++) {
                $jadwalDate = (clone $startDate)->modify("+$i days"); // Menghitung tanggal untuk jadwal

                Jadwal::create([
                    'date' => $jadwalDate->format('Y-m-d 00:00:00'), // Jam diatur menjadi 00:00:00
                    'mapel_id' => $mapelIds[array_rand($mapelIds)], // Ambil mapel secara acak
                    'kelas_id' => $kelasIds[array_rand($kelasIds)], // Ambil kelas secara acak
                    'kelas_kategori_id' => $kelasKategoriIds[array_rand($kelasKategoriIds)], // Ambil kategori secara acak
                ]);
            }
        }
    }
}
