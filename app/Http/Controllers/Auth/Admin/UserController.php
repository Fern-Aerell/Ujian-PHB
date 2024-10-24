<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Enums\UserType as EnumsUserType;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Guru;
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

        $query = User::query();

        $query->where('id', '!=', Auth::user()->id);

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
            'value' => $user_list
        ]);
    }

    public function add(): Response
    {
        return Inertia::render('Auth/Admin/Users/UserEditor/UserEditor');
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
        ]);

        $user = User::create([
            'type' => $request->type,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email ? $request->email : null,
            'email_verified_at' => $request->email_verified_at,
            'password' => Crypt::encryptString($request->password),
        ]);

        if(EnumsUserType::from($request->type) == EnumsUserType::ADMIN)
        {
            Admin::create([
                'user_id' => $user->id
            ]);
        }
        else if(EnumsUserType::from($request->type) == EnumsUserType::GURU)
        {
            Guru::create([
                'user_id' => $user->id
            ]);
        }
        else if(EnumsUserType::from($request->type) == EnumsUserType::MURID)
        {
            Murid::create([
                'user_id' => $user->id
            ]);
        }

        event(new Registered($user));

        return redirect(route('user.list'));
    }

    public function edit(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return abort(404, 'User tidak ditemukan');
        }

        return Inertia::render('Auth/Admin/Users/UserEditor/UserEditor', [
            'user' => [
                'id' => $user->id,
                'type' => $user->type,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'password' => Crypt::decryptString($user->password),
            ]
        ]);
    }

    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return abort(404, 'User tidak ditemukan');
        }

        $request->validate([
            'type' => ['required', 'string', new UserType],
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'lowercase', 'max:255', Rule::unique(User::class)->ignore($user->id), new NoWhitespace],
            'email' => ['email', 'nullable', Rule::unique(User::class)->ignore($user->id)],
            'password' => ['required', 'confirmed', new Password],
        ]);

        if($user->type != $request->type)
        {
            if($user->isAdmin())
            {
                $admin = Admin::where('user_id', $user->id)->first();
                $admin->delete();
            }
            else if($user->isGuru())
            {
                $guru = Guru::where('user_id', $user->id)->first();
                $guru->delete();
            }
            else if($user->isMurid())
            {
                $murid = Murid::where('user_id', $user->id)->first();
                $murid->delete();
            }

            if(EnumsUserType::from($request->type) == EnumsUserType::ADMIN)
            {
                Admin::create([
                    'user_id' => $user->id
                ]);
            }
            else if(EnumsUserType::from($request->type) == EnumsUserType::GURU)
            {
                Guru::create([
                    'user_id' => $user->id
                ]);
            }
            else if(EnumsUserType::from($request->type) == EnumsUserType::MURID)
            {
                Murid::create([
                    'user_id' => $user->id
                ]);
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

        $user->save();

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
