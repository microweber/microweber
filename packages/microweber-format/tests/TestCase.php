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

        // The CMS get_option() helper (globally autoloaded) resolves
        // app('option_manager'); bind a minimal stub so FormatService's
        // date helpers work in this standalone package test environment.
        $app->singleton('option_manager', function () {
            return new class {
                public function get($key, $group = false, $returnFull = false, $orderby = false, $module = false)
                {
                    return $key === 'date_format' ? 'm/d/Y h:i a' : null;
                }
            };
        });
    }
}