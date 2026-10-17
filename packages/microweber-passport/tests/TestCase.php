<?php

declare(strict_types=1);

namespace MicroweberPackages\Passport\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Base test case for the microweber-passport package.
 *
 * Dual-mode: a self-contained Laravel Passport OAuth server via Orchestra
 * Testbench for standalone testing, or Microweber's Tests\TestCase inside the CMS.
 */
if (class_exists(\Orchestra\Testbench\TestCase::class) && ! trait_exists(\Tests\CreatesApplication::class)) {
    abstract class TestCase extends \Orchestra\Testbench\TestCase
    {
        use RefreshDatabase;

        protected function getPackageProviders($app): array
        {
            return [
                \Laravel\Passport\PassportServiceProvider::class,
                \MicroweberPackages\Passport\Providers\MicroweberPassportServiceProvider::class,
            ];
        }

        protected function defineEnvironment($app): void
        {
            $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
            $app['config']->set('database.default', 'testing');
            $app['config']->set('database.connections.testing', [
                'driver'   => 'sqlite',
                'database' => ':memory:',
                'prefix'   => '',
            ]);

            $app['config']->set('auth.guards.api', [
                'driver' => 'passport',
                'provider' => 'users',
            ]);
            $app['config']->set('auth.providers.users', [
                'driver' => 'eloquent',
                'model' => \MicroweberPackages\Passport\Tests\Fixtures\User::class,
            ]);
        }

        protected function defineRoutes($router): void
        {
            // The token-auth tests exercise a protected API route; the monorepo app
            // provides /api/user, so register an equivalent here for standalone runs.
            $router->get('/api/user', function (\Illuminate\Http\Request $request) {
                return $request->user();
            })->middleware('auth:api');
        }

        protected function defineDatabaseMigrations(): void
        {
            $this->loadLaravelMigrations();
            // Laravel Passport 13 only *publishes* its migrations (doesn't auto-load),
            // so load the base oauth_* migrations from the installed package explicitly.
            $passportDir = dirname((new \ReflectionClass(\Laravel\Passport\PassportServiceProvider::class))->getFileName())
                . '/../database/migrations';
            if (is_dir($passportDir)) {
                $this->loadMigrationsFrom($passportDir);
            }
            // This package's migrations (microweber columns + personal-access client row).
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }

        protected function setUp(): void
        {
            parent::setUp();

            // The auth/token tests need a users table with email + username + password.
            if (! \Illuminate\Support\Facades\Schema::hasTable('users')) {
                \Illuminate\Support\Facades\Schema::create('users', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('name')->nullable();
                    $table->string('username')->nullable();
                    $table->string('email')->unique();
                    $table->string('password');
                    $table->rememberToken();
                    $table->timestamps();
                });
            }

            // Passport needs RSA keys to sign tokens.
            try {
                $this->artisan('passport:keys', ['--force' => true]);
            } catch (\Throwable) {
                // keys already generated
            }

            // Ensure a personal-access-grant client exists (Passport 13 model:
            // a 'personal_access' grant on oauth_clients) so $user->createToken() works.
            $this->ensurePersonalAccessClient();
        }

        private function ensurePersonalAccessClient(): void
        {
            $hasClient = \Illuminate\Support\Facades\DB::table('oauth_clients')
                ->whereJsonContains('grant_types', 'personal_access')
                ->exists();

            if (! $hasClient) {
                /** @var \Laravel\Passport\ClientRepository $clients */
                $clients = app(\Laravel\Passport\ClientRepository::class);
                $clients->createPersonalAccessGrantClient('Test Personal Access Client');
            }
        }
    }
} else {
    abstract class TestCase extends \Tests\TestCase
    {
        protected function setUp(): void
        {
            parent::setUp();
        }
    }
}
