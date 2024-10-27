<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasKategori extends Model
{
    use HasFactory;

    protected $fillable = [
        'kepanjangan',
        'kependekan',
    ];

    public function murid()
    {
        return $this->hasMany(Murid::class, 'kelas_kategori_id', 'id');
    }
}
