<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'school_name',
        'activity_type',
        'activity_title',
        'activity_title_abbreviation'
    ];

    public $timestamps = false;
}
