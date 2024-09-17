<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $user = $request->user();

        $request->validate([
            'email' => ['email', 'required', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->update([
            'email' => $request->email
        ]);

        $user->save();

        $user->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
