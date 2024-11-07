<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoActivityJawaban extends Model
{
    use HasFactory;
    protected $fillable = [
        'do_activity_soal_id',
        'jawaban',
    ];

    public function doActivitySoal()
    {
        return $this->belongsTo(DoActivitySoal::class, 'do_activity_soal_id', 'id');
    }
}
