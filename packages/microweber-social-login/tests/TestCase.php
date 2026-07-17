<?php

declare(strict_types=1);

namespace MicroweberPackages\SocialLogin\Tests;

use MicroweberPackages\Core\tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
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

        // Refresh the service config
        if ($this->app->bound('social_login')) {
            /** @var \MicroweberPackages\SocialLogin\Services\SocialLoginService $service */
            $service = $this->app->make('social_login');
            $service->refreshConfig();
        }
    }
}