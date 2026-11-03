<?php

declare(strict_types=1);

namespace MicroweberPackages\Frontend\Tests;

use MicroweberPackages\Frontend\Providers\FrontendServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            FrontendServiceProvider::class,
        ];
    }
}
