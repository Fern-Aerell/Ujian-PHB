<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityMapelKelasKategoriKelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'mapel_id',
        'kelas_kategori_id',
        'kelas_id',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'id');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id', 'id');
    }

    public function kelasKategori()
    {
        return $this->belongsTo(KelasKategori::class, 'kelas_kategori_id', 'id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }
}
