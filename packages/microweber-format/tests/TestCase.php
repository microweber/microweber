<?php

namespace MicroweberPackages\Format\Tests;

use MicroweberPackages\Format\FormatServiceProvider;
use MicroweberPackages\Security\SecurityServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            SecurityServiceProvider::class,
            FormatServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
    }
}