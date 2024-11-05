<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'active',
    ];

    public function activityMapelKelasKategoriKelas()
    {
        return $this->hasMany(ActivityMapelKelasKategoriKelas::class, 'activity_id', 'id');
    }

    public function soals()
    {
        return $this->hasMany(Soal::class);
    }
}
