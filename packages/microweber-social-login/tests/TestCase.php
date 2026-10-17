<?php

declare(strict_types=1);

namespace MicroweberPackages\SocialLogin\Tests;

/**
 * Dual-mode: Orchestra Testbench for standalone package testing, or the CMS
 * harness (Core\tests\TestCase) when running inside the Microweber monorepo.
 */
if (class_exists(\Orchestra\Testbench\TestCase::class) && ! trait_exists(\Tests\CreatesApplication::class)) {
    abstract class TestCase extends \Orchestra\Testbench\TestCase
    {
        protected function getPackageProviders($app): array
        {
            return [
                \Laravel\Socialite\SocialiteServiceProvider::class,
                \MicroweberPackages\SocialLogin\Providers\SocialLoginServiceProvider::class,
            ];
        }

        protected function getEnvironmentSetUp($app): void
        {
            $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
        }

        protected function setUp(): void
        {
            parent::setUp();
            $this->configureSocialLogin();
        }

        private function configureSocialLogin(): void
        {
            $this->app['config']->set('social-login.providers.google', [
                'enabled'       => true,
                'client_id'     => 'test-google-id',
                'client_secret' => 'test-google-secret',
            ]);

            if ($this->app->bound(\MicroweberPackages\SocialLogin\Contracts\SocialLoginServiceContract::class)) {
                /** @var \MicroweberPackages\SocialLogin\Services\SocialLoginService $service */
                $service = $this->app->make(\MicroweberPackages\SocialLogin\Contracts\SocialLoginServiceContract::class);
                $service->refreshConfig();
            }
        }
    }
} else {
    abstract class TestCase extends \MicroweberPackages\Core\tests\TestCase
    {
        protected function setUp(): void
        {
            parent::setUp();

            // Ensure the social login package is registered and configured for tests
            $this->app['config']->set('social-login.providers.google', [
                'enabled'       => true,
                'client_id'     => 'test-google-id',
                'client_secret' => 'test-google-secret',
            ]);

            if ($this->app->bound(\MicroweberPackages\SocialLogin\Contracts\SocialLoginServiceContract::class)) {
                /** @var \MicroweberPackages\SocialLogin\Services\SocialLoginService $service */
                $service = $this->app->make(\MicroweberPackages\SocialLogin\Contracts\SocialLoginServiceContract::class);
                $service->refreshConfig();
            }
        }
    }
}
