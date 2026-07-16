<?php

namespace MicroweberPackages\Fortify\Tests;

use MicroweberPackages\Core\tests\TestCase as MicroweberTestCase;
use MicroweberPackages\Fortify\Contracts\TwoFactorAuthenticatable;

abstract class TestCase extends MicroweberTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Create a test user using the CMS User model.
     *
     * @param  array<string, mixed>  $overrides
     * @return TwoFactorAuthenticatable&\Illuminate\Foundation\Auth\User
     */
    protected function createFortifyTestUser(array $overrides = []): \Illuminate\Foundation\Auth\User
    {
        $model = config('auth.providers.users.model', \MicroweberPackages\User\Models\User::class);
        /** @var TwoFactorAuthenticatable&\Illuminate\Foundation\Auth\User $user */
        $user = $model::create(array_merge([
            'username' => 'fortify_test_' . uniqid(),
            'email' => 'fortify_test_' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'is_active' => 1,
        ], $overrides));
        return $user;
    }

    /**
     * Clean up a test user by resetting 2FA columns and deleting.
     */
    protected function cleanupFortifyUser(\Illuminate\Foundation\Auth\User $user): void
    {
        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();
        $user->delete();
    }
}