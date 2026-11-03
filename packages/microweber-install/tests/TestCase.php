<?php

declare(strict_types=1);

namespace MicroweberPackages\Install\Tests;

use MicroweberPackages\Install\InstallServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            InstallServiceProvider::class,
        ];
    }
}
