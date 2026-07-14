<?php

namespace MicroweberPackages\Fortify\Tests;

use MicroweberPackages\Core\tests\TestCase as MicroweberTestCase;

abstract class TestCase extends MicroweberTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Create a test user using the CMS User model.
     */
    protected function createFortifyTestUser(array $overrides = []): \Illuminate\Foundation\Auth\User
    {
        $model = config('auth.providers.users.model', \MicroweberPackages\User\Models\User::class);
        return $model::create(array_merge([
            'username' => 'fortify_test_' . uniqid(),
            'email' => 'fortify_test_' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'is_active' => 1,
        ], $overrides));
    }

    protected function cleanupFortifyUser($user): void
    {
        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();
        $user->delete();
    }
}