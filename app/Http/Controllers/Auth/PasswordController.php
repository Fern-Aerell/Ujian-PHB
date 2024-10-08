<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Rules\CurrentPassword;
use App\Rules\Password;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', new CurrentPassword],
            'password' => ['required', new Password, 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Crypt::encryptString($validated['password']),
        ]);

        return back();
    }
}
