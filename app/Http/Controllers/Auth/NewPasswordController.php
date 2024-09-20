<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetToken;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): Response
    {
        $plainToken = $request->route('token');
        $email = $request->query('email');
        $passwordResetToken = PasswordResetToken::where('email', $email)->first();

        if(!$passwordResetToken) {
            return abort(404, "Email '".$email."' tidak memiliki reset token.");
        }

        $hashToken = $passwordResetToken->token;

        if(!Hash::check($plainToken, $hashToken)) {
            return abort(404, "Token tidak valid.");
        }

        return Inertia::render('Guest/ResetPassword', [
            'success_msg' => session('success_msg'),
            'failed_msg' => session('failed_msg'),
            'username' => $passwordResetToken->user->username,
            'token' => $plainToken
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'username' => 'required',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        var_dump($request->token);
        echo '<br>';
        var_dump($request->username);
        echo '<br>';
        var_dump($request->password);
        echo '<br>';
        die;

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('username', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Crypt::encryptString($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success_msg', __($status));
        }

        return redirect()->back()->with('failed_msg', trans($status));
    }
}
