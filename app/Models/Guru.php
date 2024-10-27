<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relasi ke GuruMapelKelasKategoriKelas
    public function guruMapelKelasKategoriKelas()
    {
        return $this->hasMany(GuruMapelKelasKategoriKelas::class, 'guru_id', 'id');
    }
}
