<?php

declare(strict_types=1);

namespace MicroweberPackages\Dusk\Tests;

use MicroweberPackages\Dusk\DuskPackageServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class StandaloneTestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            DuskPackageServiceProvider::class,
        ];
    }
}
