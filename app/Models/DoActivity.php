<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoActivity extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'activity_id',
        'finished'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'id');
    }

    public function doActivitySoals()
    {
        return $this->hasMany(DoActivitySoal::class, 'do_activity_id', 'id');
    }
}
