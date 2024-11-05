<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivitySoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'soal_id',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'id');
    }

    // Relasi ke model Soal
    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }
}
