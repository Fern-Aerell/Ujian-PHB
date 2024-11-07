<?php

namespace App\Http\Controllers\Auth;

use App\Enums\SoalType;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityMapelKelasKategoriKelas;
use App\Models\ActivitySoal;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\KelasKategori;
use App\Models\Mapel;
use App\Models\Soal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index()
    {
        // Mendapatkan user yang sedang login
        $auth_user = User::find(Auth::user()->id);

        if ($auth_user->isAdmin()) {
            // Admin: Ambil semua data Activity dengan relasi yang dibutuhkan
            $activitys_raw = Activity::with([
                'user',
                'activityMapelKelasKategoriKelas.mapel',
                'activityMapelKelasKategoriKelas.kelasKategori',
                'activityMapelKelasKategoriKelas.kelas',
            ])->get();
        } elseif ($auth_user->isGuru()) {
            // Guru: Ambil data berdasarkan mapel, kelas, dan kelas_kategori yang diampu guru
            $guruData = $auth_user->guru->guruMapelKelasKategoriKelas;
            $mapelIds = $guruData->pluck('mapel_id')->toArray();
            $kelasIds = $guruData->pluck('kelas_id')->toArray();
            $kelasKategoriIds = $guruData->pluck('kelas_kategori_id')->toArray();

            $activitys_raw = Activity::with([
                'activityMapelKelasKategoriKelas.mapel',
                'activityMapelKelasKategoriKelas.kelasKategori',
                'activityMapelKelasKategoriKelas.kelas',
            ])
                ->whereHas('activityMapelKelasKategoriKelas', function ($query) use ($mapelIds, $kelasIds, $kelasKategoriIds) {
                    $query->whereIn('mapel_id', $mapelIds)
                        ->whereIn('kelas_id', $kelasIds)
                        ->whereIn('kelas_kategori_id', $kelasKategoriIds);
                })
                ->get();
        } elseif ($auth_user->isMurid()) {
            // Murid: Ambil data berdasarkan kelas dan kelas_kategori murid
            $muridData = $auth_user->murid;
            $kelasId = $muridData->kelas_id;
            $kelasKategoriId = $muridData->kelas_kategori_id;

            $activitys_raw = Activity::with([
                'activityMapelKelasKategoriKelas.mapel',
                'activityMapelKelasKategoriKelas.kelasKategori',
                'activityMapelKelasKategoriKelas.kelas',
            ])
                ->whereHas('activityMapelKelasKategoriKelas', function ($query) use ($kelasId, $kelasKategoriId) {
                    $query->where('kelas_id', $kelasId)
                        ->where('kelas_kategori_id', $kelasKategoriId);
                })
                ->where('active', true)
                ->get();
        }

        $activitys = [];

        // Proses data Activity untuk menambahkan informasi mapel, kelas, dan kelas kategori
        foreach ($activitys_raw as $activity_raw) {
            $mapel_kelas_kategori_kelas = [];

            foreach ($activity_raw->activityMapelKelasKategoriKelas as $activityMapelKelasKategoriKelas) {
                $mapel_kelas_kategori_kelas[] = [
                    'mapel' => "{$activityMapelKelasKategoriKelas->mapel->kependekan} ({$activityMapelKelasKategoriKelas->mapel->kepanjangan})",
                    'kelas' => "{$activityMapelKelasKategoriKelas->kelas->bilangan}/{$activityMapelKelasKategoriKelas->kelas->romawi} ({$activityMapelKelasKategoriKelas->kelas->pengucapan})",
                    'kelas_kategori' => "{$activityMapelKelasKategoriKelas->kelasKategori->kependekan} ({$activityMapelKelasKategoriKelas->kelasKategori->kepanjangan})",
                ];
            }

            $activitys[] = [
                'id' => $activity_raw->id,
                'author' => $activity_raw->user->name,
                'active' => (bool) $activity_raw->active,
                'mapel_kelas_kategori_kelas' => $mapel_kelas_kategori_kelas,
                'created_at' => $activity_raw->created_at->format('d M Y'),
            ];
        }

        // Return data ke tampilan Inertia
        return inertia('Auth/Activity/Activity', [
            'activitys' => $activitys
        ]);
    }

    public function tambahIndex()
    {
        $auth_user = User::find(Auth::user()->id);

        // Inisialisasi variabel kosong untuk mapel, kelas, dan kelas kategori
        $mapelIds = [];
        $kelasIds = [];
        $kelasKategoriIds = [];

        // Jika user adalah admin, ambil semua mapel, kelas, dan kelas kategori
        if ($auth_user->isAdmin()) {
            $mapels = Mapel::all()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => "{$item->kependekan} ({$item->kepanjangan})",
                    'kependekan' => $item->kependekan,
                    'kepanjangan' => $item->kepanjangan,
                ];
            });

            $kelas = Kelas::all()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => "{$item->bilangan}/{$item->romawi} ({$item->pengucapan})",
                    'bilangan' => $item->bilangan,
                    'romawi' => $item->romawi,
                    'pengucapan' => $item->pengucapan,
                ];
            });

            $kelas_kategoris = KelasKategori::all()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => "{$item->kependekan} ({$item->kepanjangan})",
                    'kependekan' => $item->kependekan,
                    'kepanjangan' => $item->kepanjangan,
                ];
            });
        } else {
            // Jika user adalah guru, ambil data mapel, kelas, dan kelas kategori yang relevan
            $guruData = $auth_user->guru->guruMapelKelasKategoriKelas;

            $mapelIds = $guruData->pluck('mapel_id')->toArray();
            $kelasIds = $guruData->pluck('kelas_id')->toArray();
            $kelasKategoriIds = $guruData->pluck('kelas_kategori_id')->toArray();

            // Filter mapel, kelas, dan kelas kategori sesuai dengan data guru
            $mapels = Mapel::whereIn('id', $mapelIds)->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => "{$item->kependekan} ({$item->kepanjangan})",
                    'kependekan' => $item->kependekan,
                    'kepanjangan' => $item->kepanjangan,
                ];
            });

            $kelas = Kelas::whereIn('id', $kelasIds)->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => "{$item->bilangan}/{$item->romawi} ({$item->pengucapan})",
                    'bilangan' => $item->bilangan,
                    'romawi' => $item->romawi,
                    'pengucapan' => $item->pengucapan,
                ];
            });

            $kelas_kategoris = KelasKategori::whereIn('id', $kelasKategoriIds)->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => "{$item->kependekan} ({$item->kepanjangan})",
                    'kependekan' => $item->kependekan,
                    'kepanjangan' => $item->kepanjangan,
                ];
            });
        }

        // Query soal sesuai filter
        $soals_raw = Soal::with(['user', 'mapel', 'kelas', 'kelasKategori', 'jawabans'])
            ->where(function ($query) use ($auth_user) {
                // Filter soal yang dibuat oleh guru
                $query->where('user_id', $auth_user->id);
            })
            ->orWhere(function ($query) use ($mapelIds, $kelasIds, $kelasKategoriIds) {
                // Kelompokkan semua kondisi berdasarkan mapel_id, kelas_id, dan kelas_kategori_id
                $query->where(function ($q) use ($mapelIds) {
                    $q->whereIn('mapel_id', $mapelIds)
                        ->orWhereNull('mapel_id');
                })
                    ->where(function ($q) use ($kelasIds) {
                        $q->whereIn('kelas_id', $kelasIds)
                            ->orWhereNull('kelas_id');
                    })
                    ->where(function ($q) use ($kelasKategoriIds) {
                        $q->whereIn('kelas_kategori_id', $kelasKategoriIds)
                            ->orWhereNull('kelas_kategori_id');
                    });
            })
            ->get();

        // Proses data soal untuk menambahkan tags dan jawabans
        $soals = $soals_raw->map(function ($item) {

            $tags = [];
            $jawabans = [];

            foreach ($item->jawabans as $jawaban) {
                $jawabans[] = [
                    'id' => $jawaban->id,
                    'content' => $jawaban->content,
                    'correct' => (bool)$jawaban->correct,
                ];
            }

            if ($item->mapel != null) {
                $tags[] = $item->mapel->kependekan;
                $tags[] = $item->mapel->kepanjangan;
            }

            if ($item->kelas != null) {
                $tags[] = $item->kelas->bilangan;
                $tags[] = $item->kelas->romawi;
                $tags[] = $item->kelas->pengucapan;
            }

            if ($item->kelasKategori != null) {
                $tags[] = $item->kelasKategori->kependekan;
                $tags[] = $item->kelasKategori->kepanjangan;
            }

            return [
                'id' => $item->id,
                'content' => $item->content,
                'type' => $item->type,
                'tags' => $tags,
                'author' => $item->user->name,
                'jawabans' => $jawabans,
            ];
        });

        // Return data ke tampilan Inertia
        return inertia('Auth/Activity/ActivityEditor', [
            'mapels' => $mapels,
            'kelas' => $kelas,
            'kelas_kategoris' => $kelas_kategoris,
            'soals' => $soals,
        ]);
    }

    public function editIndex(int $id)
    {
        $auth_user = User::find(Auth::user()->id);

        // Mendapatkan data activity beserta relasi yang diperlukan
        $activity_raw = Activity::with([
            'activityMapelKelasKategoriKelas.mapel',
            'activityMapelKelasKategoriKelas.kelasKategori',
            'activityMapelKelasKategoriKelas.kelas',
            'activitySoals.soal.user',
            'activitySoals.soal.mapel',
            'activitySoals.soal.kelas',
            'activitySoals.soal.kelasKategori',
            'activitySoals.soal.jawabans',
        ])->find($id);

        if (!$activity_raw) return abort(404, 'Activity tidak ada.');

        // Jika user adalah admin, ambil semua mapel, kelas, dan kelas kategori
        if ($auth_user->isAdmin()) {
            $mapels = Mapel::all()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => "{$item->kependekan} ({$item->kepanjangan})",
                    'kependekan' => $item->kependekan,
                    'kepanjangan' => $item->kepanjangan,
                ];
            });

            $kelas = Kelas::all()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => "{$item->bilangan}/{$item->romawi} ({$item->pengucapan})",
                    'bilangan' => $item->bilangan,
                    'romawi' => $item->romawi,
                    'pengucapan' => $item->pengucapan,
                ];
            });

            $kelas_kategoris = KelasKategori::all()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => "{$item->kependekan} ({$item->kepanjangan})",
                    'kependekan' => $item->kependekan,
                    'kepanjangan' => $item->kepanjangan,
                ];
            });
        } else {
            // Jika user adalah guru, ambil data mapel, kelas, dan kelas kategori yang relevan
            $guruData = $auth_user->guru->guruMapelKelasKategoriKelas;

            $mapelIds = $guruData->pluck('mapel_id')->toArray();
            $kelasIds = $guruData->pluck('kelas_id')->toArray();
            $kelasKategoriIds = $guruData->pluck('kelas_kategori_id')->toArray();

            // Filter mapel, kelas, dan kelas kategori sesuai dengan data guru
            $mapels = Mapel::whereIn('id', $mapelIds)->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => "{$item->kependekan} ({$item->kepanjangan})",
                    'kependekan' => $item->kependekan,
                    'kepanjangan' => $item->kepanjangan,
                ];
            });

            $kelas = Kelas::whereIn('id', $kelasIds)->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => "{$item->bilangan}/{$item->romawi} ({$item->pengucapan})",
                    'bilangan' => $item->bilangan,
                    'romawi' => $item->romawi,
                    'pengucapan' => $item->pengucapan,
                ];
            });

            $kelas_kategoris = KelasKategori::whereIn('id', $kelasKategoriIds)->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => "{$item->kependekan} ({$item->kepanjangan})",
                    'kependekan' => $item->kependekan,
                    'kepanjangan' => $item->kepanjangan,
                ];
            });
        }

        // Mapping mapel, kelas, kategori kelas berdasarkan activity
        $mapel_kelas_kategori_kelas = [];
        foreach ($activity_raw->activityMapelKelasKategoriKelas as $activityMapelKelasKategoriKelas) {
            $mapel_kelas_kategori_kelas[] = [
                'mapel' => [
                    'id' => $activityMapelKelasKategoriKelas->mapel->id,
                    'text' => "{$activityMapelKelasKategoriKelas->mapel->kependekan} ({$activityMapelKelasKategoriKelas->mapel->kepanjangan})",
                    'kependekan' => $activityMapelKelasKategoriKelas->mapel->kependekan,
                    'kepanjangan' => $activityMapelKelasKategoriKelas->mapel->kepanjangan,
                ],
                'kelas' => [
                    'id' => $activityMapelKelasKategoriKelas->kelas->id,
                    'text' => "{$activityMapelKelasKategoriKelas->kelas->bilangan}/{$activityMapelKelasKategoriKelas->kelas->romawi}",
                    'bilangan' => $activityMapelKelasKategoriKelas->kelas->bilangan,
                    'romawi' => $activityMapelKelasKategoriKelas->kelas->romawi,
                    'pengucapan' => $activityMapelKelasKategoriKelas->kelas->pengucapan,
                ],
                'kelas_kategori' => [
                    'id' => $activityMapelKelasKategoriKelas->kelasKategori->id,
                    'text' => "{$activityMapelKelasKategoriKelas->kelasKategori->kependekan} ({$activityMapelKelasKategoriKelas->kelasKategori->kepanjangan})",
                    'kependekan' => $activityMapelKelasKategoriKelas->kelasKategori->kependekan,
                    'kepanjangan' => $activityMapelKelasKategoriKelas->kelasKategori->kepanjangan,
                ],
            ];
        }

        // Mengambil soal dan jawabannya
        $soalActivity = [];
        foreach ($activity_raw->activitySoals as $activitySoal) {
            $tags = [];
            $jawabans = [];

            if ($activitySoal->soal->mapel) {
                $tags[] = $activitySoal->soal->mapel->kependekan;
                $tags[] = $activitySoal->soal->mapel->kepanjangan;
            }
            if ($activitySoal->soal->kelas) {
                $tags[] = $activitySoal->soal->kelas->bilangan;
                $tags[] = $activitySoal->soal->kelas->romawi;
            }
            if ($activitySoal->soal->kelasKategori) {
                $tags[] = $activitySoal->soal->kelasKategori->kependekan;
                $tags[] = $activitySoal->soal->kelasKategori->kepanjangan;
            }

            foreach ($activitySoal->soal->jawabans as $jawaban) {
                $jawabans[] = [
                    'id' => $jawaban->id,
                    'content' => $jawaban->content,
                    'correct' => (bool)$jawaban->correct,
                ];
            }

            $soalActivity[] = [
                'id' => $activitySoal->soal->id,
                'content' => $activitySoal->soal->content,
                'author' => $activitySoal->soal->user->name,
                'type' => $activitySoal->soal->type,
                'tags' => $tags,
                'jawabans' => $jawabans,
            ];
        }

        $activity = [
            'id' => $activity_raw->id,
            'active' => (bool)$activity_raw->active,
            'mapel_kelas_kategori_kelas' => $mapel_kelas_kategori_kelas,
            'created_at' => $activity_raw->created_at->format('d M Y'),
            'soals' => $soalActivity,
        ];

        // Filter soal untuk form input (jika diperlukan)
        $soals = Soal::with(['mapel', 'kelas', 'kelasKategori', 'jawabans', 'user'])->get()->map(function ($item) {
            $tags = [];
            $jawabans = [];

            foreach ($item->jawabans as $jawaban) {
                $jawabans[] = [
                    'id' => $jawaban->id,
                    'content' => $jawaban->content,
                    'correct' => (bool)$jawaban->correct,
                ];
            }

            if ($item->mapel) {
                $tags[] = $item->mapel->kependekan;
                $tags[] = $item->mapel->kepanjangan;
            }
            if ($item->kelas) {
                $tags[] = $item->kelas->bilangan;
                $tags[] = $item->kelas->romawi;
            }
            if ($item->kelasKategori) {
                $tags[] = $item->kelasKategori->kependekan;
                $tags[] = $item->kelasKategori->kepanjangan;
            }

            return [
                'id' => $item->id,
                'content' => $item->content,
                'type' => $item->type,
                'tags' => $tags,
                'author' => $item->user->name,
                'jawabans' => $jawabans,
            ];
        });

        // Return data ke tampilan Inertia
        return inertia('Auth/Activity/ActivityEditor', [
            'activity' => $activity,
            'mapels' => $mapels,
            'kelas' => $kelas,
            'kelas_kategoris' => $kelas_kategoris,
            'soals' => $soals,
        ]);
    }

    public function tambah(Request $request)
    {
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
            'soals' => [
                'required',
                'array',
                'min:1'
            ],
            'soals.*.id' => [
                'required',
                'int'
            ],
            'active' => [
                'required',
                'boolean',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value === true) {
                        // Periksa apakah semua mapel_kelas_kategori_kelas ada dalam jadwal
                        foreach ($request->input('mapel_kelas_kategori_kelas') as $item) {
                            $exists = Jadwal::where('mapel_id', $item['mapel']['id'])
                                ->where('kelas_id', $item['kelas']['id'])
                                ->where('kelas_kategori_id', $item['kelas_kategori']['id'])
                                ->exists();

                            if (!$exists) {
                                $mapel = Mapel::find($item['mapel']['id']);
                                $kelasKategori = KelasKategori::find($item['kelas_kategori']['id']);
                                $kelas = Kelas::find($item['kelas']['id']);

                                $fail("Tidak ada jadwal yang cocok untuk mapel {$mapel->kependekan}, kelas kategori {$kelasKategori->kependekan}, dan kelas {$kelas->bilangan}. Aktivasi hanya bisa dilakukan jika ada jadwal.");
                                break;
                            }
                        }
                    }
                }
            ]
        ]);

        $activity = Activity::create([
            'user_id' => Auth::user()->id,
            'active' => $request->active,
        ]);

        $activityMapelKelasKategoriKelas = ActivityMapelKelasKategoriKelas::where('activity_id', $activity->id);
        $activityMapelKelasKategoriKelas->delete();

        $activitySoal = ActivitySoal::where('activity_id', $activity->id);
        $activitySoal->delete();

        foreach ($request->mapel_kelas_kategori_kelas as $mapel_kelas_kategori_kelas) {
            ActivityMapelKelasKategoriKelas::create([
                'activity_id' => $activity->id,
                'mapel_id' => $mapel_kelas_kategori_kelas['mapel']['id'],
                'kelas_id' => $mapel_kelas_kategori_kelas['kelas']['id'],
                'kelas_kategori_id' => $mapel_kelas_kategori_kelas['kelas_kategori']['id'],
            ]);
        }

        foreach ($request->soals as $soals) {
            ActivitySoal::create([
                'activity_id' => $activity->id,
                'soal_id' => $soals['id']
            ]);
        }

        return redirect()->back();
    }

    public function edit(Request $request, int $id)
    {
        $activity = Activity::find($id);
        if (!$activity) return abort(404, 'Activity tidak ada.');

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
            'soals' => [
                'required',
                'array',
                'min:1'
            ],
            'soals.*.id' => [
                'required',
                'int'
            ],
            'active' => [
                'required',
                'boolean',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value === true) {
                        // Periksa apakah semua mapel_kelas_kategori_kelas ada dalam jadwal
                        foreach ($request->input('mapel_kelas_kategori_kelas') as $item) {
                            $exists = Jadwal::where('mapel_id', $item['mapel']['id'])
                                ->where('kelas_id', $item['kelas']['id'])
                                ->where('kelas_kategori_id', $item['kelas_kategori']['id'])
                                ->exists();

                            if (!$exists) {
                                $mapel = Mapel::find($item['mapel']['id']);
                                $kelasKategori = KelasKategori::find($item['kelas_kategori']['id']);
                                $kelas = Kelas::find($item['kelas']['id']);

                                $fail("Tidak ada jadwal yang cocok untuk mapel {$mapel->kependekan}, kelas kategori {$kelasKategori->kependekan}, dan kelas {$kelas->bilangan}. Aktivasi hanya bisa dilakukan jika ada jadwal.");
                                break;
                            }
                        }
                    }
                }
            ]
        ]);

        $activity->update([
            'active' => $request->active,
        ]);

        $activityMapelKelasKategoriKelas = ActivityMapelKelasKategoriKelas::where('activity_id', $activity->id);
        $activityMapelKelasKategoriKelas->delete();

        $activitySoal = ActivitySoal::where('activity_id', $activity->id);
        $activitySoal->delete();

        foreach ($request->mapel_kelas_kategori_kelas as $mapel_kelas_kategori_kelas) {
            ActivityMapelKelasKategoriKelas::create([
                'activity_id' => $activity->id,
                'mapel_id' => $mapel_kelas_kategori_kelas['mapel']['id'],
                'kelas_id' => $mapel_kelas_kategori_kelas['kelas']['id'],
                'kelas_kategori_id' => $mapel_kelas_kategori_kelas['kelas_kategori']['id'],
            ]);
        }

        foreach ($request->soals as $soals) {
            ActivitySoal::create([
                'activity_id' => $activity->id,
                'soal_id' => $soals['id']
            ]);
        }

        return redirect()->back();
    }

    public function hapus(int $id)
    {
        $activity = Activity::find($id);
        if (!$activity) return abort(404, 'Activity tidak ada.');
        $activity->delete();
        return redirect(route('activity'));
    }

    public function doIndex(Request $request, int $id) 
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

        if(!$activity) return abort(404, 'Activity tidak ada');

        $transformedActivity = [
            'id' => $activity->id,
            'author' => $activity->user->name,
            'mapel_kelas_kategori_kelas' => $activity->activityMapelKelasKategoriKelas->map(function ($item) {
                return [
                    'mapel' => $item->mapel->kependekan,
                    'kelas' => $item->kelas->bilangan,
                    'kelas_kategori' => $item->kelasKategori->kependekan,
                ];
            }),
            'soals' => $activity->activitySoals->map(function ($activitySoal) {
                $soals = [
                    'id' => $activitySoal->soal->id,
                    'content' => $activitySoal->soal->content,
                    'type' => $activitySoal->soal->type
                ];

                if($activitySoal->soal->type == SoalType::ISIAN_SINGKAT) {
                    $soals['jawaban'] = '';
                }else{
                    $soals['jawabans'] = $activitySoal->soal->jawabans->map(function ($jawaban) use($activitySoal) {
                        return [
                            'id' => $jawaban->id,
                            'content' => $jawaban->content,
                            'correct' => false,
                        ];
                    });
                }

                return $soals;
            }),
        ];

        return inertia("Auth/Activity/DoActivity", [
            'activity' => $transformedActivity
        ]);
    }

    public function doFinish(Request $request, int $id)
    {
        $request->validate([
            'soals' => [
                'required',
                'array'
            ]
        ]);

        var_dump($request->all());
        die;
    }
}
 