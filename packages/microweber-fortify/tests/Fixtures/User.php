<?php

namespace MicroweberPackages\Fortify\Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use MicroweberPackages\Fortify\Traits\HasTwoFactorAuthentication;

class User extends Authenticatable
{
    use Notifiable, HasTwoFactorAuthentication;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'is_admin',
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
            'password' => 'hashed',
        ];
    }
}