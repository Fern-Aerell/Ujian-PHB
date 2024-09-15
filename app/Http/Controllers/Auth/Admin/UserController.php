<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
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
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $user_list = $query->paginate($max, ['*'], 'page', $page);

        return Inertia::render('Auth/Admin/User/List', [
            'user_list' => $user_list
        ]);
    }

    public function add(): Response
    {
        return Inertia::render('Auth/Admin/User/Add');
    }

    public function addAdmin(): Response
    {
        return Inertia::render('Auth/Admin/User/AddAdmin');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => ['required', 'string', new UserType],
            'name' => 'required|string|max:255',
            'username' => 'required|string|lowercase|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'type' => $request->type,
            'name' => $request->name,
            'username' => $request->username,
            'password' => Crypt::encryptString($request->password),
        ]);

        event(new Registered($user));

        return redirect(route('user.list'));
    }

    public function edit(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return abort(404, 'User tidak ditemukan');
        }

        return Inertia::render('Auth/Admin/User/Edit', [
            'user' => [
                'id' => $user->id,
                'type' => $user->type,
                'name' => $user->name,
                'username' => $user->username,
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
            'username' => ['required', 'string', 'lowercase', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'type' => $request->type,
            'name' => $request->name,
            'username' => $request->username,
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

        return redirect()->back();
    }
}
