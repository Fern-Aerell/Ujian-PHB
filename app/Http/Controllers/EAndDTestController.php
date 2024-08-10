<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;
use Inertia\Response;

class EAndDTestController extends Controller
{
    public function index(Request $request): Response {

        return Inertia::render('E&DTest', [
            'text_enkripsi' => session('text_enkripsi'),
            'hasil_enkripsi' => session('hasil_enkripsi'),
            'text_dekripsi' => session('text_dekripsi'),
            'hasil_dekripsi' => session('hasil_dekripsi')
        ]);

    }

    public function encrypt(Request $request): RedirectResponse {
        $textEncrypt = $request->string('text_enkripsi');
        $result = '';

        try {
            $result = Crypt::encryptString($textEncrypt);
        }catch(EncryptException $error) {
            $result = $error->getMessage();
        }

        return back()->with([
            'text_enkripsi' => $textEncrypt,
            'hasil_enkripsi' => $result
        ]);
    }

    public function decrypt(Request $request): RedirectResponse {
        $textDecrypt = base64_decode(base64_encode($request->string('text_dekripsi')));

        $result = '';

        try {
            $result = Crypt::decryptString($textDecrypt);
        }catch(DecryptException $error) {
            $result = $error->getMessage();
        }

        return back()->with([
            'text_dekripsi' => $textDecrypt,
            'hasil_dekripsi' => $result
        ]);
    }
}
