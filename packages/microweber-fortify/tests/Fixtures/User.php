<?php

namespace MicroweberPackages\Fortify\Tests\Fixtures;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use MicroweberPackages\Fortify\Traits\HasTwoFactorAuthentication;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasTwoFactorAuthentication;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'is_admin',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}