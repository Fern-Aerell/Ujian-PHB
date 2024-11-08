<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\DoActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RaporController extends Controller
{
    public function index()
    {
        $doActivitys = DoActivity::with([
            'user',
            'activity.user',
            'activity.activityMapelKelasKategoriKelas.mapel',
            'activity.activityMapelKelasKategoriKelas.kelasKategori',
            'activity.activityMapelKelasKategoriKelas.kelas',
            'activity.activitySoals.soal.mapel',
            'activity.activitySoals.soal.kelas',
            'activity.activitySoals.soal.kelasKategori',
            'activity.activitySoals.soal.jawabans',
            'doActivitySoals.soal',
            'doActivitySoals.soal.jawabans',
            'doActivitySoals.doActivityJawabans',
        ])
        ->where('finished', true)
        ->get();

        $datas = [];

        foreach ($doActivitys as $doActivity) {
            $total_soal = count($doActivity->doActivitySoals);
            $nilai = 0; // Inisialisasi nilai

            // Hitung nilai berdasarkan jawaban
            foreach ($doActivity->doActivitySoals as $doActivitySoal) {
                $soal = $doActivitySoal->soal;
                $jawabanBenar = [];
                $jumlahBenar = 0;
                foreach($soal->jawabans as $jawaban) {
                    if((bool)$jawaban->correct) {
                        $jawabanBenar[] = $jawaban->content;
                    }
                }

                foreach($doActivitySoal->doActivityJawabans as $doActivityJawaban) {
                    if(in_array($doActivityJawaban->jawaban, $jawabanBenar)) {
                        $jumlahBenar++;
                    }
                }

                if($jumlahBenar == count($jawabanBenar)) {
                    $nilai++;
                }
            }

            $nilai = ($nilai / $total_soal) * 100;

            $datas[] = [
                'author' => $doActivity->activity->user->name,
                'date' => $doActivity->created_at->format('d M Y'),
                'mapel_kelas_kategori_kelas' => $doActivity->activity->activityMapelKelasKategoriKelas->map(function ($item) {
                    return [
                        'mapel' => $item->mapel->kependekan,
                        'kelas_kategori' => $item->kelasKategori->kependekan,
                        'kelas' => $item->kelas->bilangan,
                    ];
                }),
                'nilai' => round($nilai, 2)
            ];
        }

        return inertia('Auth/Rapor', [
            'datas' => $datas
        ]);
    }
}
