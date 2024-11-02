<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'mapel_id',
        'kelas_id',
        'kelas_kategori_id',
    ];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function kelasKategori()
    {
        return $this->belongsTo(KelasKategori::class, 'kelas_kategori_id');
    }
}
