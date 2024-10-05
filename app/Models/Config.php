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
        'activity_title_abbreviation',
        'exam_date_start',
        'exam_date_end',
        'exam_time_start',
        'exam_time_end',
    ];

    public $timestamps = false;
}
