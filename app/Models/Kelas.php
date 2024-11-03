<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'bilangan',
        'romawi',
        'pengucapan'
    ];

    public function murid()
    {
        return $this->hasMany(Murid::class, 'kelas_id', 'id');
    }

    // Relasi ke GuruMapelKelasKategoriKelas
    public function guruMapelKelasKategoriKelas()
    {
        return $this->hasMany(GuruMapelKelasKategoriKelas::class, 'kelas_id', 'id');
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function soals()
    {
        return $this->hasMany(Soal::class);
    }
}
