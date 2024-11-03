<?php

namespace App\Http\Controllers\Auth;

use App\Models\Jadwal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    // View
    public function index()
    {
        $user = User::with([
            'admin',
            'guru.guruMapelKelasKategoriKelas',
            'murid',
        ])->find(Auth::user()->id);

        // Ambil jadwal dengan relasi
        $jadwalsQuery = Jadwal::with(['mapel', 'kelas', 'kelasKategori']);

        // Filter berdasarkan tipe user
        if ($user->isMurid()) {
            // Filter jadwal sesuai kelas dan kategori kelas murid
            $jadwalsQuery->where('kelas_id', $user->murid->kelas_id)
                ->where('kelas_kategori_id', $user->murid->kelas_kategori_id);
        } elseif ($user->isGuru()) {
            // Ambil semua kelas dan kategori yang diajar oleh guru ini
            $guruKelasKategoriKelas = $user->guru->guruMapelKelasKategoriKelas;
        
            // Ambil mapel yang diajar oleh guru ini
            $mapelIds = $guruKelasKategoriKelas->pluck('mapel_id')->toArray();
        
            // Filter jadwal berdasarkan kelas, kategori, dan mapel yang diajar
            $jadwalsQuery->whereIn('kelas_id', $guruKelasKategoriKelas->pluck('kelas_id')->toArray())
                         ->whereIn('kelas_kategori_id', $guruKelasKategoriKelas->pluck('kelas_kategori_id')->toArray())
                         ->whereIn('mapel_id', $mapelIds); // Filter berdasarkan mapel yang diajar
        }

        // Eksekusi query dan group by date
        $jadwals = $jadwalsQuery->get();

        $datas = $jadwals->groupBy(function ($item) {
            return Carbon::parse($item->date)->locale('id')->translatedFormat('l, d M Y');
        })->map(function ($items, $date) {
            return [
                'date' => $date,
                'jadwals' => $items->map(function ($item) {
                    return [
                        'mapel' => "{$item->mapel->kependekan} ({$item->mapel->kepanjangan})",
                        'kelas' => "{$item->kelas->bilangan}/{$item->kelas->romawi} ({$item->kelas->pengucapan})",
                        'kelas_kategori' => "{$item->kelasKategori->kependekan} ({$item->kelasKategori->kepanjangan})",
                    ];
                })->toArray(),
            ];
        })->values()->toArray();

        return inertia('Auth/Jadwal', compact('datas'));
    }


    // Tambah
    public function tambah(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'mapel_id' => 'required|exists:mapels,id',
            'kelas_id' => 'required|exists:kelas,id',
            'kelas_kategori_id' => 'required|exists:kelas_kategoris,id',
        ]);

        // Cek unik
        $exists = Jadwal::where('mapel_id', $validatedData['mapel_id'])
            ->where('kelas_id', $validatedData['kelas_id'])
            ->where('kelas_kategori_id', $validatedData['kelas_kategori_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['kelas_kategori_id' => 'Jadwal dengan kombinasi ini sudah ada'])->withInput();
        }

        Jadwal::create($validatedData);

        return redirect()->back();
    }

    // Edit
    public function edit(Request $request, int $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $validatedData = $request->validate([
            'date' => 'sometimes|date',
            'mapel_id' => 'sometimes|exists:mapels,id',
            'kelas_id' => 'sometimes|exists:kelas,id',
            'kelas_kategori_id' => 'sometimes|exists:kelas_kategoris,id',
        ]);

        // Cek unik kalau ada perubahan di mapel_id, kelas_id, atau kelas_kategori_id
        if (isset($validatedData['mapel_id']) || isset($validatedData['kelas_id']) || isset($validatedData['kelas_kategori_id'])) {
            $exists = Jadwal::where('mapel_id', $validatedData['mapel_id'] ?? $jadwal->mapel_id)
                ->where('kelas_id', $validatedData['kelas_id'] ?? $jadwal->kelas_id)
                ->where('kelas_kategori_id', $validatedData['kelas_kategori_id'] ?? $jadwal->kelas_kategori_id)
                ->where('id', '!=', $id) // Pastikan bukan jadwal yang sedang di-edit
                ->exists();

            if ($exists) {
                return redirect()->back()->withErrors(['kelas_kategori_id' => 'Jadwal dengan kombinasi ini sudah ada'])->withInput();
            }
        }

        $jadwal->update($validatedData);

        return redirect()->back();
    }

    // Hapus
    public function hapus(int $id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return redirect()->back();
    }
}
