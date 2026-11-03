<?php

declare(strict_types=1);

namespace MicroweberPackages\LaravelHelperFunctions\Tests;

use MicroweberPackages\LaravelHelperFunctions\Providers\LaravelHelperFunctionsServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelHelperFunctionsServiceProvider::class,
        ];
    }
}
