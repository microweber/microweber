<?php

declare(strict_types=1);

namespace MicroweberPackages\Console\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * This package ships console Command classes but no service provider of
     * its own (the commands are registered by the host CMS application), so
     * no package provider is booted here.
     */
    protected function getPackageProviders($app): array
    {
        return [];
    }
}
