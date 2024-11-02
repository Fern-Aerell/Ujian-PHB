<?php

namespace App\Http\Middleware;

use App\Enums\UserType;
use App\Models\Config;
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
            'config' => Config::first(),
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'type' => $request->user()->type,
                    'name' => $request->user()->name,
                    'username' => $request->user()->username,
                    'email' => $request->user()->email,
                    'email_verified_at' => $request->user()->email_verified_at,
                    'password' => Crypt::decryptString($request->user()->password),
                    'admin' => $request->user()->admin,
                    'guru' => $request->user()->guru ? $request->user()->guru->load('guruMapelKelasKategoriKelas.mapel', 'guruMapelKelasKategoriKelas.kelasKategori', 'guruMapelKelasKategoriKelas.kelas') : null,
                    'murid' => $request->user()->murid,
                ] : null
            ],
        ];
    }
}
