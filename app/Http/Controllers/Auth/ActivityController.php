<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityMapelKelasKategoriKelas;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\KelasKategori;
use App\Models\Mapel;
use App\Models\Soal;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index() {
        $activitys = [];

        $activitys_raw = Activity::with([
            'activityMapelKelasKategoriKelas.mapel',
            'activityMapelKelasKategoriKelas.kelasKategori',
            'activityMapelKelasKategoriKelas.kelas',
        ])->get();

        foreach($activitys_raw as $activity_raw) {
            
            $mapel_kelas_kategori_kelas = [];

            foreach($activity_raw->activityMapelKelasKategoriKelas as $activityMapelKelasKategoriKelas) {

                $mapel_kelas_kategori_kelas[] = [
                    'mapel' => "{$activityMapelKelasKategoriKelas->mapel->kependekan} ({$activityMapelKelasKategoriKelas->mapel->kepanjangan})",
                    'kelas' => "{$activityMapelKelasKategoriKelas->kelas->bilangan}/{$activityMapelKelasKategoriKelas->kelas->romawi} ({$activityMapelKelasKategoriKelas->kelas->pengucapan})",
                    'kelas_kategori' => "{$activityMapelKelasKategoriKelas->kelasKategori->kependekan} ({$activityMapelKelasKategoriKelas->kelasKategori->kepanjangan})",
                ];

            }

            $activitys[] = [
                'id' => $activity_raw->id,
                'active' => (bool)$activity_raw->active,
                'mapel_kelas_kategori_kelas' => $mapel_kelas_kategori_kelas,
                'created_at' => $activity_raw->created_at->format('d M Y'),
            ];

        }

        return inertia('Auth/Activity/Activity', [
            'activitys' => $activitys
        ]);
    }

    public function tambahIndex() {
        $mapels = Mapel::all()->map(function($item) {
            return [
                'id' => $item->id,
                'text' => "{$item->kependekan} ({$item->kepanjangan})",
            ];
        });

        $kelas = Kelas::all()->map(function($item) {
            return [
                'id' => $item->id,
                'text' => "{$item->bilangan}/{$item->romawi} ({$item->pengucapan})",
            ];
        });

        $kelas_kategoris = KelasKategori::all()->map(function($item) {
            return [
                'id' => $item->id,
                'text' => "{$item->kependekan} ({$item->kepanjangan})",
            ];
        });

        $soals = Soal::with(['mapel', 'kelas', 'kelasKategori', 'jawabans'])->get()->map(function($item) {
            
            $jawabans = [];

            foreach($item->jawabans as $jawaban) {
                $jawabans[] = [
                    'id' => $jawaban->id,
                    'content' => $jawaban->content,
                    'correct' => (bool)$jawaban->correct,
                ];
            }

            return [
                'id' => $item->id,
                'content' => $item->content,
                'type' => $item->type,
                'jawabans' => $jawabans,
            ];
        });

        return inertia('Auth/Activity/ActivityEditor', [
            'mapels' => $mapels,
            'kelas' => $kelas,
            'kelas_kategoris' => $kelas_kategoris,
            'soals' => $soals,
        ]);
    }

    public function editIndex(int $id) {
        $activity_raw = Activity::with([
            'activityMapelKelasKategoriKelas.mapel',
            'activityMapelKelasKategoriKelas.kelasKategori',
            'activityMapelKelasKategoriKelas.kelas',
        ])->find($id);

        if(!$activity_raw) return abort(404, 'Activity tidak ada.');

        $mapels = Mapel::all()->map(function($item) {
            return [
                'id' => $item->id,
                'text' => "{$item->kependekan} ({$item->kepanjangan})",
            ];
        });

        $kelas = Kelas::all()->map(function($item) {
            return [
                'id' => $item->id,
                'text' => "{$item->bilangan}/{$item->romawi} ({$item->pengucapan})",
            ];
        });

        $kelas_kategoris = KelasKategori::all()->map(function($item) {
            return [
                'id' => $item->id,
                'text' => "{$item->kependekan} ({$item->kepanjangan})",
            ];
        });

        $mapel_kelas_kategori_kelas = [];

        foreach($activity_raw->activityMapelKelasKategoriKelas as $activityMapelKelasKategoriKelas) {
            $mapel_kelas_kategori_kelas[] = [
                'mapel' => [
                    'id' => $activityMapelKelasKategoriKelas->mapel->id,
                    'text' => "{$activityMapelKelasKategoriKelas->mapel->kependekan} ({$activityMapelKelasKategoriKelas->mapel->kepanjangan})",
                ],
                'kelas' => [
                    'id' => $activityMapelKelasKategoriKelas->kelas->id,
                    'text' => "{$activityMapelKelasKategoriKelas->kelas->bilangan}/{$activityMapelKelasKategoriKelas->kelas->romawi} ({$activityMapelKelasKategoriKelas->kelas->pengucapan})",
                ],
                'kelas_kategori' => [
                    'id' => $activityMapelKelasKategoriKelas->kelas->id,
                    'text' => "{$activityMapelKelasKategoriKelas->kelasKategori->kependekan} ({$activityMapelKelasKategoriKelas->kelasKategori->kepanjangan})",
                ],
            ];
        }

        $activity = [
            'id' => $activity_raw->id,
            'active' => (bool)$activity_raw->active,
            'mapel_kelas_kategori_kelas' => $mapel_kelas_kategori_kelas,
            'created_at' => $activity_raw->created_at->format('d M Y'),
        ];

        return inertia('Auth/Activity/ActivityEditor', [
            'activity' => $activity,
            'mapels' => $mapels,
            'kelas' => $kelas,
            'kelas_kategoris' => $kelas_kategoris
        ]);
    }

    public function tambah(Request $request) {
        $request->validate([
            'mapel_kelas_kategori_kelas' => [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) {
                    foreach ($value as $item) {
                        // Mengambil nama mapel dari tabel mapel
                        $mapel = Mapel::find($item['mapel']['id']);
                        // Mengambil nama kelas kategori dari tabel kelas kategori
                        $kelasKategori = KelasKategori::find($item['kelas_kategori']['id']);
                        // Mengambil nama kelas dari tabel kelas
                        $kelas = Kelas::find($item['kelas']['id']);

                        $exists = ActivityMapelKelasKategoriKelas::where('mapel_id', $item['mapel']['id'])
                            ->where('kelas_kategori_id', $item['kelas_kategori']['id'])
                            ->where('kelas_id', $item['kelas']['id'])
                            ->exists();

                        if ($exists) {
                            $fail("Data mapel {$mapel->kependekan}, kelas kategori {$kelasKategori->kependekan}, dan kelas {$kelas->bilangan}, sudah digunakan pada ujian lain.");
                        }
                    }
                },
            ],
            'mapel_kelas_kategori_kelas.*.mapel.id' => [
                'required',
                'int'
            ],
            'mapel_kelas_kategori_kelas.*.kelas.id' => [
                'required',
                'int'
            ],
            'mapel_kelas_kategori_kelas.*.kelas_kategori.id' => [
                'required',
                'int'
            ],
        ]);

        $activity = Activity::create([
            'active' => false,
        ]);

        $activityMapelKelasKategoriKelas = ActivityMapelKelasKategoriKelas::where('activity_id', $activity->id);
        $activityMapelKelasKategoriKelas->delete();

        foreach($request->mapel_kelas_kategori_kelas as $mapel_kelas_kategori_kelas) {
            ActivityMapelKelasKategoriKelas::create([
                'activity_id' => $activity->id,
                'mapel_id' => $mapel_kelas_kategori_kelas['mapel']['id'],
                'kelas_id' => $mapel_kelas_kategori_kelas['kelas']['id'],
                'kelas_kategori_id' => $mapel_kelas_kategori_kelas['kelas_kategori']['id'],
            ]);
        }

        return redirect()->back();
    }

    public function edit(Request $request, int $id) {
        $activity = Activity::find($id);
        if(!$activity) return abort(404, 'Activity tidak ada.');

        $request->validate([
            'mapel_kelas_kategori_kelas' => [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) use ($activity) {
                    foreach ($value as $item) {
                        // Mengambil nama mapel dari tabel mapel
                        $mapel = Mapel::find($item['mapel']['id']);
                        // Mengambil nama kelas kategori dari tabel kelas kategori
                        $kelasKategori = KelasKategori::find($item['kelas_kategori']['id']);
                        // Mengambil nama kelas dari tabel kelas
                        $kelas = Kelas::find($item['kelas']['id']);

                        // Cek apakah entri sudah ada untuk mapel, kelas kategori, dan kelas yang sama, tetapi untuk guru yang berbeda
                        $exists = ActivityMapelKelasKategoriKelas::where('mapel_id', $item['mapel']['id'])
                            ->where('kelas_kategori_id', $item['kelas_kategori']['id'])
                            ->where('kelas_id', $item['kelas']['id'])
                            ->where('activity_id', '!=', $activity->id) // Pastikan ID guru berbeda
                            ->exists();

                        if ($exists) {
                            $fail("Data mapel {$mapel->kependekan}, kelas kategori {$kelasKategori->kependekan}, dan kelas {$kelas->bilangan}, sudah digunakan pada ujian lain.");
                        }
                    }
                },
            ],
            'mapel_kelas_kategori_kelas.*.mapel.id' => [
                'required',
                'int'
            ],
            'mapel_kelas_kategori_kelas.*.kelas.id' => [
                'required',
                'int'
            ],
            'mapel_kelas_kategori_kelas.*.kelas_kategori.id' => [
                'required',
                'int'
            ],
        ]);

        $activity->update([
            'active' => false,
        ]);

        $activityMapelKelasKategoriKelas = ActivityMapelKelasKategoriKelas::where('activity_id', $activity->id);
        $activityMapelKelasKategoriKelas->delete();

        foreach($request->mapel_kelas_kategori_kelas as $mapel_kelas_kategori_kelas) {
            ActivityMapelKelasKategoriKelas::create([
                'activity_id' => $activity->id,
                'mapel_id' => $mapel_kelas_kategori_kelas['mapel']['id'],
                'kelas_id' => $mapel_kelas_kategori_kelas['kelas']['id'],
                'kelas_kategori_id' => $mapel_kelas_kategori_kelas['kelas_kategori']['id'],
            ]);
        }

        return redirect()->back();
    }

    public function hapus(int $id) {
        $activity = Activity::find($id);
        if(!$activity) return abort(404, 'Activity tidak ada.');
        $activity->delete();
        return redirect(route('activity'));
    }
}
