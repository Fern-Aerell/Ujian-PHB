<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\CurrentPassword;
use App\Rules\NoWhitespace;
use App\Rules\Password;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class AccountController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Auth/Account/Edit');
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {

        $id = Auth::user()->id;
        $user = User::find($id);

        if (!$user) {
            return abort(404, 'User tidak ditemukan');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'lowercase', 'max:255', Rule::unique(User::class)->ignore($id), new NoWhitespace],
            'email' => ['required', 'email', Rule::unique(User::class)->ignore($id)],
            'password' => ['required', 'confirmed', new Password],
        ]);

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'email_verified_at' => $user->email != $request->email ? null : $request->email_verified_at,
            'password' => Crypt::encryptString($request->password),
        ]);

        $user->save();
        
        return Redirect::route('account.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', new CurrentPassword],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
