<?php

declare(strict_types=1);

namespace MicroweberPackages\Pagination\Tests;

use MicroweberPackages\Pagination\PaginationServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            PaginationServiceProvider::class,
        ];
    }
}