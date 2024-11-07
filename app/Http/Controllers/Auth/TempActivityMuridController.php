<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\TempActivityMurid;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TempActivityMuridController extends Controller
{
    public function index(int $id)
    {


        $activity = Activity::with([
            'user',
            'activityMapelKelasKategoriKelas.mapel',
            'activityMapelKelasKategoriKelas.kelasKategori',
            'activityMapelKelasKategoriKelas.kelas',
            'activitySoals.soal.mapel',
            'activitySoals.soal.kelas',
            'activitySoals.soal.kelasKategori',
            'activitySoals.soal.jawabans',
        ])->find($id);

        return inertia('Auth/Activity/TempActivityMurid', [
            'activity' => $activity,
        ]);
    }
}
