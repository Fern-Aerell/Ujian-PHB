<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Models\Jadwal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JadwalController extends Controller
{
    // Tambah
    public function tambah(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'mapel_id' => 'required|exists:mapels,id',
            'kelas_id' => 'required|exists:kelas,id',
            'kelas_kategori_id' => 'required|exists:kelas_kategoris,id',
        ]);

        // Cek unik
        $exists = Jadwal::where('mapel_id', $validatedData['mapel_id'])
                        ->where('kelas_id', $validatedData['kelas_id'])
                        ->where('kelas_kategori_id', $validatedData['kelas_kategori_id'])
                        ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['kelas_kategori_id' => 'Jadwal dengan kombinasi ini sudah ada'])->withInput();
        }

        Jadwal::create($validatedData);

        return redirect()->back();
    }

    // Edit
    public function edit(Request $request, int $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $validatedData = $request->validate([
            'date' => 'sometimes|date',
            'mapel_id' => 'sometimes|exists:mapels,id',
            'kelas_id' => 'sometimes|exists:kelas,id',
            'kelas_kategori_id' => 'sometimes|exists:kelas_kategoris,id',
        ]);

        // Cek unik kalau ada perubahan di mapel_id, kelas_id, atau kelas_kategori_id
        if (isset($validatedData['mapel_id']) || isset($validatedData['kelas_id']) || isset($validatedData['kelas_kategori_id'])) {
            $exists = Jadwal::where('mapel_id', $validatedData['mapel_id'] ?? $jadwal->mapel_id)
                            ->where('kelas_id', $validatedData['kelas_id'] ?? $jadwal->kelas_id)
                            ->where('kelas_kategori_id', $validatedData['kelas_kategori_id'] ?? $jadwal->kelas_kategori_id)
                            ->where('id', '!=', $id) // Pastikan bukan jadwal yang sedang di-edit
                            ->exists();

            if ($exists) {
                return redirect()->back()->withErrors(['kelas_kategori_id' => 'Jadwal dengan kombinasi ini sudah ada'])->withInput();
            }
        }

        $jadwal->update($validatedData);

        return redirect()->back();
    }

    // Hapus
    public function hapus(int $id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return redirect()->back();
    }
}
