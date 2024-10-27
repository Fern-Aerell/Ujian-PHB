<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Murid extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kelas_id',
        'kelas_kategori_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }

    public function kelasKategori()
    {
        return $this->belongsTo(KelasKategori::class, 'kelas_kategori_id', 'id');
    }
}
