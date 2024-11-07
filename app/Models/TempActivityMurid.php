<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempActivityMurid extends Model
{
    use HasFactory;

    protected $fillable = [
        'murid_id',
        'activity_id',
        'data',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array'
        ];
    }

    public function murid()
    {
        return $this->belongsTo(Murid::class, 'murid_id', 'id');
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'id');
    }
}
