<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type'
    ];

    
    /**
     * Get the users associated with the user type.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'type', 'id');
    }

}
