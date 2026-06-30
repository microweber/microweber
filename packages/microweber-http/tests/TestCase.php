<?php

namespace MicroweberPackages\Http\Tests;

use MicroweberPackages\Http\HttpServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            HttpServiceProvider::class,
        ];
    }
}