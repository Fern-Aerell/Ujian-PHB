<?php

namespace App\Models;

use App\Enums\SoalType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mapel_id',
        'kelas_id',
        'kelas_kategori_id',
        'type',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'type' => SoalType::class
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

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

    // Relasi ke model Jawaban
    public function jawabans()
    {
        return $this->hasMany(Jawaban::class);
    }
}
