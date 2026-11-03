<?php

declare(strict_types=1);

namespace MicroweberPackages\MicroweberUI\Tests;

use MicroweberPackages\MicroweberUI\Providers\MicroweberUIServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            MicroweberUIServiceProvider::class,
        ];
    }
}
