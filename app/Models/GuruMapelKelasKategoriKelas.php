<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruMapelKelasKategoriKelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'guru_id',
        'mapel_id',
        'kelas_kategori_id',
        'kelas_id',
    ];

    // Relasi ke model Guru
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'id');
    }

    // Relasi ke model Mapel
    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id', 'id');
    }

    // Relasi ke model KelasKategori
    public function kelasKategori()
    {
        return $this->belongsTo(KelasKategori::class, 'kelas_kategori_id', 'id');
    }

    // Relasi ke model Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }
}
