<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
    use HasFactory;

    protected $fillable = [
        'soal_id',
        'type',
        'content',
        'correct',
    ];

    // Relasi ke model Soal
    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }
}
