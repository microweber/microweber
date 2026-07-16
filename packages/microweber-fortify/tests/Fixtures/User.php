<?php

namespace MicroweberPackages\Fortify\Tests\Fixtures;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use MicroweberPackages\Fortify\Contracts\TwoFactorAuthenticatable;
use MicroweberPackages\Fortify\Traits\HasTwoFactorAuthentication;

/**
 * Test fixture User model for standalone package tests.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $username
 * @property int $is_admin
 * @property int $is_active
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property \Illuminate\Support\Carbon|null $two_factor_confirmed_at
 */
class User extends Authenticatable implements TwoFactorAuthenticatable
{
    use HasFactory, Notifiable, HasTwoFactorAuthentication;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'is_admin',
        'is_active',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Determine if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return (int) $this->is_admin === 1;
    }
}