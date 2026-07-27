<?php

namespace MicroweberPackages\Security\Tests;

use MicroweberPackages\Security\SecurityServiceProvider;

// Support both standalone (Orchestra\Testbench) and CMS-integrated testing.
if (class_exists(\Orchestra\Testbench\TestCase::class)) {
    abstract class TestCase extends \Orchestra\Testbench\TestCase
    {
        protected function getPackageProviders($app): array
        {
            return [
                SecurityServiceProvider::class,
            ];
        }

        protected function getEnvironmentSetUp($app): void
        {
            $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
        }
    }
} else {
    abstract class TestCase extends \Tests\TestCase
    {
        // CMS boots SecurityServiceProvider via MicroweberServiceProvider.
    }
}
