<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean'
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function activityMapelKelasKategoriKelas()
    {
        return $this->hasMany(ActivityMapelKelasKategoriKelas::class, 'activity_id', 'id');
    }

    public function activitySoals()
    {
        return $this->hasMany(ActivitySoal::class, 'activity_id', 'id');
    }

    public function doActivitys()
    {
        return $this->hasMany(DoActivity::class, 'do_activity_id', 'id');
    }
}
