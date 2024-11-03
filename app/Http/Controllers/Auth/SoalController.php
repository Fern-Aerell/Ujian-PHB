<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\KelasKategori;
use App\Models\Mapel;
use App\Models\Soal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SoalController extends Controller
{
    public function index()
    {
        $auth_user = User::find(Auth::user()->id);
        if($auth_user->isAdmin()) {
            $soals_raw = Soal::with(['user', 'mapel', 'kelas', 'kelasKategori'])->get();
        }else{
            $soals_raw = Soal::with(['user', 'mapel', 'kelas', 'kelasKategori'])->where('user_id', Auth::user()->id)->get();
        }

        $soals = [];

        // Proses data soal untuk menambahkan tags
        foreach ($soals_raw as $soal_raw) {
            $tags = [];

            if ($soal_raw->mapel != null) {
                $tags[] = $soal_raw->mapel->kependekan;
                $tags[] = $soal_raw->mapel->kepanjangan;
            }

            if ($soal_raw->kelas != null) {
                $tags[] = $soal_raw->kelas->bilangan;
                $tags[] = $soal_raw->kelas->romawi;
                $tags[] = $soal_raw->kelas->pengucapan;
            }

            if ($soal_raw->kelasKategori != null) {
                $tags[] = $soal_raw->kelasKategori->kependekan;
                $tags[] = $soal_raw->kelasKategori->kepanjangan;
            }

            $soals[] = [
                'id' => $soal_raw->id,
                'author' => $soal_raw->user->name,
                'tags' => $tags,
                'content' => $soal_raw->content,
            ];
        }

        return inertia('Auth/Soal/Soal', compact('soals'));
    }

    private function compactMapelsKelasKelasKategoris(int $id)
    {
        $user = User::with([
            'guru.guruMapelKelasKategoriKelas',
        ])->find($id);

        // Jika user adalah guru, batasi data berdasarkan guruMapelKelasKategoriKelas
        if ($user->isGuru()) {
            $guruData = $user->guru->guruMapelKelasKategoriKelas;

            // Ambil ID dari mapel, kelas, dan kelas kategori yang diajarkan guru
            $mapelIds = $guruData->pluck('mapel_id')->unique();
            $kelasIds = $guruData->pluck('kelas_id')->unique();
            $kelasKategoriIds = $guruData->pluck('kelas_kategori_id')->unique();

            // Filter data mapel, kelas, dan kelas kategori berdasarkan ID yang diajarkan
            $mapels_raw = Mapel::whereIn('id', $mapelIds)->get();
            $kelass_raw = Kelas::whereIn('id', $kelasIds)->get();
            $kelas_kategoris_raw = KelasKategori::whereIn('id', $kelasKategoriIds)->get();
        } else {
            // Jika bukan guru, ambil semua data
            $mapels_raw = Mapel::all();
            $kelass_raw = Kelas::all();
            $kelas_kategoris_raw = KelasKategori::all();
        }

        $mapels = [];
        $kelas = [];
        $kelas_kategoris = [];

        foreach ($mapels_raw as $mapel_raw) {
            $mapels[] = [
                "id" => $mapel_raw->id,
                "text" => "{$mapel_raw->kependekan} ({$mapel_raw->kepanjangan})",
            ];
        }

        foreach ($kelas_kategoris_raw as $kelas_kategori_raw) {
            $kelas_kategoris[] = [
                "id" => $kelas_kategori_raw->id,
                "text" => "{$kelas_kategori_raw->kependekan} ({$kelas_kategori_raw->kepanjangan})",
            ];
        }

        foreach ($kelass_raw as $kelas_raw) {
            $kelas[] = [
                "id" => $kelas_raw->id,
                "text" => "{$kelas_raw->bilangan}/{$kelas_raw->romawi} ({$kelas_raw->pengucapan})",
            ];
        }

        return compact('mapels', 'kelas', 'kelas_kategoris');
    }

    public function tambahIndex()
    {
        $auth_user = User::find(Auth::user()->id);
        if($auth_user->isAdmin()) return abort(403, 'Admin tidak dapat membuat soal.');
        return inertia(
            'Auth/Soal/SoalEditor',
            $this->compactMapelsKelasKelasKategoris(Auth::user()->id)
        );
    }


    public function editIndex(int $id)
    {
        $soal = Soal::with('user')->find($id);
        if (!$soal) return abort(404, 'Soal tidak ada.');
        $auth_user = User::find(Auth::user()->id);
        if(!$auth_user->isAdmin() && $soal->user_id != $auth_user->id) return abort(403, 'Tidak dapat mengedit soal yang dibuat oleh user lain.');
        $soal->author = $soal->user->name;
        return inertia('Auth/Soal/SoalEditor', 
            compact('soal') + 
            $this->compactMapelsKelasKelasKategoris($soal->user_id)
        );
    }

    // Tambah
    public function tambah(Request $request)
    {
        $auth_user = User::find(Auth::user()->id);
        if($auth_user->isAdmin()) return abort(403, 'Admin tidak dapat menambahkan soal.');
        $request->validate([
            'mapel_id' => 'nullable|exists:mapels,id',
            'kelas_id' => 'nullable|exists:kelas,id',
            'kelas_kategori_id' => 'nullable|exists:kelas_kategoris,id',
            'content' => 'required|string',
        ]);
        Soal::create($request->all() + ['user_id' => Auth::user()->id]);
        return redirect()->back();
    }

    // Edit
    public function edit(Request $request, int $id)
    {
        $soal = Soal::find($id);
        if (!$soal) return abort(404, 'Soal tidak ada.');
        $auth_user = User::find(Auth::user()->id);
        if(!$auth_user->isAdmin() && $soal->user_id != $auth_user->id) return abort(403, 'Tidak dapat mengedit soal yang dibuat oleh user lain.');
        $request->validate([
            'mapel_id' => 'nullable|exists:mapels,id',
            'kelas_id' => 'nullable|exists:kelas,id',
            'kelas_kategori_id' => 'nullable|exists:kelas_kategoris,id',
            'content' => 'required|string',
        ]);
        $soal->update($request->all());
        return redirect()->back();
    }

    // Hapus
    public function hapus(int $id)
    {
        $soal = Soal::find($id);
        if (!$soal) return abort(404, 'Soal tidak ada.');
        if($soal->user_id != Auth::user()->id) return abort(403, 'Tidak dapat menghapus soal yang dibuat oleh user lain.');
        $soal->delete();
        return redirect(route('soal'));
    }
}
