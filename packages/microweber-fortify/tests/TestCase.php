<?php

namespace MicroweberPackages\Fortify\Tests;

use MicroweberPackages\Fortify\Contracts\TwoFactorAuthenticatable;

/**
 * Shared Fortify test-user helpers (used by both the standalone Testbench base
 * and the CMS base below).
 */
trait FortifyTestUserHelpers
{
    /**
     * Create a test user using the configured user model.
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

/**
 * Dual-mode: Orchestra Testbench (with a lightweight User fixture) for standalone
 * package testing, or the CMS harness (Core\tests\TestCase, real User model) inside
 * the Microweber monorepo.
 */
if (class_exists(\Orchestra\Testbench\TestCase::class) && ! trait_exists(\Tests\CreatesApplication::class)) {
    abstract class TestCase extends \Orchestra\Testbench\TestCase
    {
        use FortifyTestUserHelpers;

        protected function getPackageProviders($app): array
        {
            return [
                \Livewire\LivewireServiceProvider::class,
                \Laravel\Fortify\FortifyServiceProvider::class,
                \MicroweberPackages\Fortify\FortifyServiceProvider::class,
            ];
        }

        protected function getEnvironmentSetUp($app): void
        {
            $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
            $app['config']->set('database.default', 'testing');
            $app['config']->set('database.connections.testing', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]);
            // Use the standalone test User fixture instead of the CMS User model.
            $app['config']->set('auth.providers.users.model', \MicroweberPackages\Fortify\Tests\Fixtures\User::class);

            // Ensure the package's fortify + microweber-fortify configs are present
            // (provider mergeConfigFrom timing differs under Testbench).
            $app['config']->set('fortify', array_replace_recursive(
                (array) $app['config']->get('fortify', []),
                require __DIR__ . '/../config/fortify.php'
            ));
            $app['config']->set('microweber-fortify', array_replace_recursive(
                (array) $app['config']->get('microweber-fortify', []),
                require __DIR__ . '/../config/microweber-fortify.php'
            ));
        }

        protected function setUp(): void
        {
            parent::setUp();

            if (! \Illuminate\Support\Facades\Schema::hasTable('users')) {
                \Illuminate\Support\Facades\Schema::create('users', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('name')->nullable();
                    $table->string('username')->nullable();
                    $table->string('email')->unique();
                    $table->timestamp('email_verified_at')->nullable();
                    $table->string('password');
                    $table->integer('is_admin')->default(0);
                    $table->integer('is_active')->default(1);
                    $table->text('two_factor_secret')->nullable();
                    $table->text('two_factor_recovery_codes')->nullable();
                    $table->timestamp('two_factor_confirmed_at')->nullable();
                    $table->rememberToken();
                    $table->timestamps();
                });
            }
        }
    }
} else {
    abstract class TestCase extends \MicroweberPackages\Core\tests\TestCase
    {
        use FortifyTestUserHelpers;

        protected function setUp(): void
        {
            parent::setUp();
        }
    }
}
