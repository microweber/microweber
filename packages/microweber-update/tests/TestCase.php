<?php

declare(strict_types=1);

namespace MicroweberPackages\Update\Tests;

use MicroweberPackages\Update\Providers\UpdateMigratorServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            UpdateMigratorServiceProvider::class,
        ];
    }
}
