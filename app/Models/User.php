<?php

namespace App\Models;

use App\Enums\UserType;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'name',
        'email',
        'email_verified_at',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => UserType::class
        ];
    }

    public function resetPasswordToken() {
        return $this->hasOne(PasswordResetToken::class, 'email', 'email');
    }

    public function admin() {
        return $this->hasOne(Admin::class, 'user_id', 'id');
    }

    public function guru() {
        return $this->hasOne(Guru::class, 'user_id', 'id');
    }

    public function murid() {
        return $this->hasOne(Murid::class, 'user_id', 'id');
    }

    public function isAdmin()
    {
        return $this->type == UserType::ADMIN;
    }

    public function isGuru() {
        return $this->type == UserType::GURU;
    }

    public function isMurid() {
        return $this->type == UserType::MURID;
    }
    
}
