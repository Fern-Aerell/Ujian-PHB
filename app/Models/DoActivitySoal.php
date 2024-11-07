<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoActivitySoal extends Model
{
    use HasFactory;
    protected $fillable = [
        'do_activity_id',
        'soal_id',
        'jawaban_id',
    ];
    
    public function doActivity()
    {
        return $this->belongsTo(DoActivity::class, 'do_activity_id', 'id');
    }

    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }

    public function jawaban()
    {
        return $this->belongsTo(Jawaban::class);
    }
}
