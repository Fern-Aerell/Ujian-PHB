<?php

namespace App\Http\Middleware;

use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'type' => $request->user()->type,
                    'name' => $request->user()->name,
                    'username' => $request->user()->username,
                    'password' => Crypt::decryptString($request->user()->password),
                ] : null,
                'userTypes' => UserType::all()
            ],
        ];
    }
}
