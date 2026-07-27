<?php

namespace MicroweberPackages\Url\Tests;

use MicroweberPackages\Url\Providers\UrlServiceProvider;

// Support both standalone (Orchestra\Testbench) and CMS-integrated testing.
if (class_exists(\Orchestra\Testbench\TestCase::class)) {
    abstract class TestCase extends \Orchestra\Testbench\TestCase
    {
        protected function getPackageProviders($app): array
        {
            return [
                UrlServiceProvider::class,
            ];
        }

        protected function getEnvironmentSetUp($app): void
        {
            $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
            $app['config']->set('app.url', 'http://localhost');
        }
    }
} else {
    abstract class TestCase extends \Tests\TestCase
    {
        // CMS boots UrlServiceProvider via MicroweberServiceProvider.
    }
}
