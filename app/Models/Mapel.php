<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $fillable = [
        'kepanjangan',
        'kependekan'
    ];

    // Relasi ke GuruMapelKelasKategoriKelas
    public function guruMapelKelasKategoriKelas()
    {
        return $this->hasMany(GuruMapelKelasKategoriKelas::class, 'mapel_id', 'id');
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }
}
