<?php

declare(strict_types=1);

namespace MicroweberPackages\Passport\Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * Minimal User model for standalone package tests.
 */
class User extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function findForPassport(string $username): ?self
    {
        return $this->where('email', $username)->first();
    }
}