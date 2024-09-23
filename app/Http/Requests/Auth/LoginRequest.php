<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Rules\NoWhitespace;
use Exception;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'string', 'lowercase', 'max:255', new NoWhitespace],
            'password' => ['required', 'string'], 
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->only('id', 'password');
        $id = $credentials['id'];
        $password = $credentials['password'];

        $user = User::where('username', $id)->orWhere('email', $id)->whereNotNull('email_verified_at')->first();

        if(!$user || Crypt::decryptString($user->password) !== $password) {
            // Gagal
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'id' => trans('auth.failed'),
            ]);

            return;
        }

        // Berhasil
        Auth::login($user, $this->boolean('remember'));

        RateLimiter::clear($this->throttleKey());

        // OG :
        // if (!Auth::attempt($this->only('username', 'password'), $this->boolean('remember'))) {
        //     RateLimiter::hit($this->throttleKey());

        //     throw ValidationException::withMessages([
        //         'username' => trans('auth.failed'),
        //     ]);
        // }

        // RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'id' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('id')).'|'.$this->ip());
    }
}
