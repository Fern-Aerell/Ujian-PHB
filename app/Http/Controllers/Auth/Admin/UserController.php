<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Enums\UserType as EnumsUserType;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Guru;
use App\Models\GuruMapelKelasKategoriKelas;
use App\Models\Kelas;
use App\Models\KelasKategori;
use App\Models\Mapel;
use App\Models\Murid;
use App\Models\User;
use App\Rules\NoWhitespace;
use App\Rules\Password;
use App\Rules\UserType;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Validation\Rules;
use Inertia\Response;

class UserController extends Controller
{

    public function list(Request $request)
    {
        $max = $request->input('max', 10);
        $page = $request->input('page', 1);
        $type = $request->input('type');
        $search = $request->input('search');

        $query = User::query()->where('id', '!=', Auth::user()->id)->with([
            'murid.kelas',
            'murid.kelasKategori',
            'admin', 
            'guru.guruMapelKelasKategoriKelas.kelas',
            'guru.guruMapelKelasKategoriKelas.kelasKategori',
            'guru.guruMapelKelasKategoriKelas.mapel',
        ]); // Eager loading relasi murid, admin, dan guru

        if ($type && $type != 'all' && $type != 'semua') {
            $query->where('type', $type);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('email_verified_at', 'like', "%{$search}%")
                    ->orWhereHas('murid.kelas', function($q) use ($search) {
                        $q->where('bilangan', 'like', "%{$search}%")
                        ->orWhere('romawi', 'like', "%{$search}%")
                        ->orWhere('pengucapan', 'like', "%{$search}%")
                        ;
                    })
                    ->orWhereHas('guru.guruMapelKelasKategoriKelas.kelas', function($q) use ($search) {
                        $q->where('bilangan', 'like', "%{$search}%")
                        ->orWhere('romawi', 'like', "%{$search}%")
                        ->orWhere('pengucapan', 'like', "%{$search}%")
                        ;
                    })
                    ->orWhereHas('murid.kelasKategori', function($q) use ($search) {
                        $q->where('kepanjangan', 'like', "%{$search}%")
                        ->orWhere('kependekan', 'like', "%{$search}%")
                        ;
                    })
                    ->orWhereHas('guru.guruMapelKelasKategoriKelas.kelasKategori', function($q) use ($search) {
                        $q->where('kepanjangan', 'like', "%{$search}%")
                        ->orWhere('kependekan', 'like', "%{$search}%")
                        ;
                    })
                    ->orWhereHas('guru.guruMapelKelasKategoriKelas.mapel', function($q) use ($search) {
                        $q->where('kepanjangan', 'like', "%{$search}%")
                        ->orWhere('kependekan', 'like', "%{$search}%")
                        ;
                    })
                    ;
            });
        }

        $user_list = $query->paginate($max, ['*'], 'page', $page);

        return Inertia::render('Auth/Admin/Users/UserList/UserList', [
            // Info Data
            'mapelData' => Mapel::all(),
            'kelasData' => Kelas::all(),
            'kelasKategoriData' => KelasKategori::all(),
            // 
            'value' => $user_list
        ]);
    }

    public function add(): Response
    {
        return Inertia::render('Auth/Admin/Users/UserEditor/UserEditor', [
            // Info Data
            'mapelData' => Mapel::all(),
            'kelasData' => Kelas::all(),
            'kelasKategoriData' => KelasKategori::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => ['required', 'string', new UserType],
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'lowercase',
                'max:255',
                'unique:' . User::class,
                new NoWhitespace
            ],
            'email_verified_at' => 'nullable|date',
            'email' => 'email|nullable|unique:' . User::class,
            'password' => ['required', 'confirmed', new Password],
            // Murid
            'murid_kelas_id' => [
                EnumsUserType::from($request->type) === EnumsUserType::MURID ? 'required' : 'nullable',
                'int'
            ],
            'murid_kelas_kategori_id' => [
                EnumsUserType::from($request->type) === EnumsUserType::MURID ? 'required' : 'nullable',
                'int'
            ],
            // Guru
            'guru_mapel_kelas_kategori_kelas' => [
                EnumsUserType::from($request->type) === EnumsUserType::GURU ? 'required' : 'nullable',
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

                        $exists = GuruMapelKelasKategoriKelas::where('mapel_id', $item['mapel']['id'])
                            ->where('kelas_kategori_id', $item['kelas_kategori']['id'])
                            ->where('kelas_id', $item['kelas']['id'])
                            ->exists();

                        if ($exists) {
                            $fail("Informasi guru mapel {$mapel->kependekan}, kelas kategori {$kelasKategori->kependekan}, dan kelas {$kelas->bilangan}, sudah digunakan pada guru lain.");
                        }
                    }
                },
            ]
        ]);

        $user = User::create([
            'type' => $request->type,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email ? $request->email : null,
            'email_verified_at' => $request->email_verified_at,
            'password' => Crypt::encryptString($request->password),
        ]);

        if (EnumsUserType::from($request->type) === EnumsUserType::ADMIN) {
            // Membuat entri baru di tabel 'admin'
            $admin = Admin::create([
                'user_id' => $user->id,
            ]);

        } else if (EnumsUserType::from($request->type) === EnumsUserType::GURU) {
            // Membuat entri baru di tabel 'guru'
            $guru = Guru::create([
                'user_id' => $user->id,
            ]);

            // Mengolah data dari 'guru_mapel_kelas_kategori_kelas' setelah validasi
            foreach ($request->guru_mapel_kelas_kategori_kelas as $item) {
                GuruMapelKelasKategoriKelas::create([
                    'guru_id' => $guru->id, // ID guru yang baru dibuat
                    'mapel_id' => $item['mapel']['id'],
                    'kelas_kategori_id' => $item['kelas_kategori']['id'],
                    'kelas_id' => $item['kelas']['id'],
                ]);
            }
        } else if (EnumsUserType::from($request->type) === EnumsUserType::MURID) {
            Murid::create([
                'user_id' => $user->id,
                'kelas_id' => $request->murid_kelas_id,
                'kelas_kategori_id' => $request->murid_kelas_kategori_id,
            ]);
        }

        event(new Registered($user));

        return redirect(route('user.list'));
    }

    public function edit(Request $request, string $id)
    {
        if (Auth::user()->id == $id) {
            return abort(404, 'Tidak dapat mengedit diri sendiri.');
        }

        $user = User::with([
            'murid.kelas',
            'murid.kelasKategori',
            'admin', 
            'guru.guruMapelKelasKategoriKelas.kelas',
            'guru.guruMapelKelasKategoriKelas.kelasKategori',
            'guru.guruMapelKelasKategoriKelas.mapel',
        ])->find($id);

        if (!$user) {
            return abort(404, 'User tidak ditemukan');
        }

        return Inertia::render('Auth/Admin/Users/UserEditor/UserEditor', [
            // Info Data
            'mapelData' => Mapel::all(),
            'kelasData' => Kelas::all(),
            'kelasKategoriData' => KelasKategori::all(),
            // User Data
            'user' => [
                'id' => $user->id,
                'type' => $user->type,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'password' => Crypt::decryptString($user->password),
                'admin' => $user->admin,
                'guru' => $user->guru,
                'murid' => $user->murid,
            ],
        ]);
    }

    public function update(Request $request, string $id)
    {
        $user = User::with(['admin', 'guru', 'murid'])->find($id);
        if (!$user) {
            return abort(404, 'User tidak ditemukan');
        }

        $request->validate([
            'type' => ['required', 'string', new UserType],
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'lowercase',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
                new NoWhitespace
            ],
            'email' => ['email', 'nullable', Rule::unique(User::class)->ignore($user->id)],
            'password' => ['required', 'confirmed', new Password],
            'murid_kelas_id' => [
                EnumsUserType::from($request->type) === EnumsUserType::MURID ? 'required' : 'nullable',
                'int'
            ],
            'murid_kelas_kategori_id' => [
                EnumsUserType::from($request->type) === EnumsUserType::MURID ? 'required' : 'nullable',
                'int'
            ],
            // Guru
            'guru_mapel_kelas_kategori_kelas' => [
                EnumsUserType::from($request->type) === EnumsUserType::GURU ? 'required' : 'nullable',
                'array',
                'min:1',
                function ($attribute, $value, $fail) use ($user) {
                    if($user->guru == null) return;
                    foreach ($value as $item) {
                        // Mengambil nama mapel dari tabel mapel
                        $mapel = Mapel::find($item['mapel']['id']);
                        // Mengambil nama kelas kategori dari tabel kelas kategori
                        $kelasKategori = KelasKategori::find($item['kelas_kategori']['id']);
                        // Mengambil nama kelas dari tabel kelas
                        $kelas = Kelas::find($item['kelas']['id']);

                        // Cek apakah entri sudah ada untuk mapel, kelas kategori, dan kelas yang sama, tetapi untuk guru yang berbeda
                        $exists = GuruMapelKelasKategoriKelas::where('mapel_id', $item['mapel']['id'])
                            ->where('kelas_kategori_id', $item['kelas_kategori']['id'])
                            ->where('kelas_id', $item['kelas']['id'])
                            ->where('guru_id', '!=', $user->guru->id) // Pastikan ID guru berbeda
                            ->exists();

                        if ($exists) {
                            $fail("Informasi guru mapel {$mapel->kependekan}, kelas kategori {$kelasKategori->kependekan}, dan kelas {$kelas->bilangan}, sudah digunakan pada guru lain.");
                        }
                    }
                },
            ]
        ]);

        if ($user->type != EnumsUserType::from($request->type)) {
            if ($user->isAdmin()) {
                $admin = Admin::where('user_id', $user->id)->first();
                $admin->delete();
            } else if ($user->isGuru()) {
                $guru = Guru::where('user_id', $user->id)->first();
                $guru->delete();
            } else if ($user->isMurid()) {
                $murid = Murid::where('user_id', $user->id)->first();
                $murid->delete();
            }
        }

        $user->update([
            'type' => $request->type,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'email_verified_at' => $user->email != $request->email ? null : $request->email_verified_at,
            'password' => Crypt::encryptString($request->password),
        ]);

        if($user->isAdmin()) {
            $admin = $user->admin;
            if ($admin == null) {
                $admin = Admin::create([
                    'user_id' => $user->id
                ]);
            } else {
                $admin->update([]);
            }
        }else if ($user->isGuru()) {
            $guru = $user->guru;

            // Jika guru belum ada, buat baru
            if ($guru == null) {
                $guru = Guru::create([
                    'user_id' => $user->id
                ]);
            } else {
                // Jika guru sudah ada, update jika perlu (dapat diisi dengan data yang relevan)
                $guru->update([]); // Update dengan data yang relevan jika perlu
            }

            // Ambil semua entri lama untuk guru ini
            $existingEntries = GuruMapelKelasKategoriKelas::where('guru_id', $guru->id)->get();

            // Siapkan array untuk menyimpan data baru
            $newEntries = [];

            // Proses data baru dari request
            foreach ($request->guru_mapel_kelas_kategori_kelas as $item) {
                // Cek apakah entri sudah ada
                $exists = $existingEntries->first(function ($entry) use ($item) {
                    return $entry->mapel_id == $item['mapel']['id'] &&
                        $entry->kelas_kategori_id == $item['kelas_kategori']['id'] &&
                        $entry->kelas_id == $item['kelas']['id'];
                });

                // Jika tidak ada, tambahkan ke array data baru
                if (!$exists) {
                    $newEntries[] = [
                        'guru_id' => $guru->id,
                        'mapel_id' => $item['mapel']['id'],
                        'kelas_kategori_id' => $item['kelas_kategori']['id'],
                        'kelas_id' => $item['kelas']['id'],
                    ];
                }
            }

            // Hapus entri lama yang tidak ada di request
            foreach ($existingEntries as $existing) {
                $existsInRequest = collect($request->guru_mapel_kelas_kategori_kelas)->first(function ($item) use ($existing) {
                    return $item['mapel']['id'] == $existing->mapel_id &&
                        $item['kelas_kategori']['id'] == $existing->kelas_kategori_id &&
                        $item['kelas']['id'] == $existing->kelas_id;
                });

                if (!$existsInRequest) {
                    // Hapus entri lama
                    $existing->delete();
                }
            }

            // Buat entri baru
            foreach ($newEntries as $entry) {
                GuruMapelKelasKategoriKelas::create($entry);
            }
        } else if ($user->isMurid()) {
            $murid = $user->murid;
            if ($murid == null) {
                Murid::create([
                    'user_id' => $user->id,
                    'kelas_id' => $request->murid_kelas_id,
                    'kelas_kategori_id' => $request->murid_kelas_kategori_id,
                ]);
            } else {
                $murid->update([
                    'kelas_id' => $request->murid_kelas_id,
                    'kelas_kategori_id' => $request->murid_kelas_kategori_id,
                ]);
            }
        }

        return redirect(route('user.list'));
    }


    public function delete(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return abort(404, 'User tidak ditemukan');
        }

        $user->delete();

        return redirect(route('user.list'));
    }
}
