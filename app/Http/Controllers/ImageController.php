<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        // 1. Validasi input agar hanya file gambar yang diterima
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // 2. Ambil file dari request
        $image = $request->file('image');

        // 3. Tentukan nama unik untuk file (misal: waktu upload + nama asli file)
        $imageName = time() . '_' . $image->getClientOriginalName();

        // 4. Pindahkan file ke storage/images
        $image->storeAs('images', $imageName, 'public');

        // 5. Buat URL ke file yang diupload
        $imageUrl = asset('storage/images/' . $imageName);

        // 6. Return URL gambar yang sudah diupload
        return response()->json(['image_url' => $imageUrl], 200);
    }
}
