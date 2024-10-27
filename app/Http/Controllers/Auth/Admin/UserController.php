<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Enums\UserType as EnumsUserType;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KelasKategori;
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

        $query = User::query()->where('id', '!=', Auth::user()->id)->with(['murid', 'admin', 'guru']); // Eager loading relasi murid, admin, dan guru

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
                    ->orWhere('email_verified_at', 'like', "%{$search}%");
            });
        }

        $user_list = $query->paginate($max, ['*'], 'page', $page);

        return Inertia::render('Auth/Admin/Users/UserList/UserList', [
            // Info Data
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
            'kelasData' => Kelas::all(),
            'kelasKategoriData' => KelasKategori::all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
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
            'murid_kelas_id' => [
                EnumsUserType::from($request->type) === EnumsUserType::MURID ? 'required' : 'nullable',
                'int'
            ],
            'murid_kelas_kategori_id' => [
                EnumsUserType::from($request->type) === EnumsUserType::MURID ? 'required' : 'nullable',
                'int'
            ],
        ]);

        $user = User::create([
            'type' => $request->type,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email ? $request->email : null,
            'email_verified_at' => $request->email_verified_at,
            'password' => Crypt::encryptString($request->password),
        ]);

        if (EnumsUserType::from($request->type) === EnumsUserType::MURID) {
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
        if(Auth::user()->id == $id) {
            return abort(404, 'Tidak dapat mengedit diri sendiri.');
        }

        $user = User::with(['murid', 'admin', 'guru'])->find($id);

        if (!$user) {
            return abort(404, 'User tidak ditemukan');
        }

        return Inertia::render('Auth/Admin/Users/UserEditor/UserEditor', [
            // Info Data
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
        ]);

        $user->update([
            'type' => $request->type,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'email_verified_at' => $user->email != $request->email ? null : $request->email_verified_at,
            'password' => Crypt::encryptString($request->password),
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

        if ($user->isMurid()) {
            $murid = $user->murid;
            if($murid == null) {
                Murid::create([
                    'user_id' => $user->id,
                    'kelas_id' => $request->murid_kelas_id,
                    'kelas_kategori_id' => $request->murid_kelas_kategori_id,
                ]);
            }else{
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
