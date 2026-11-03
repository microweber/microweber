<?php

declare(strict_types=1);

namespace MicroweberPackages\Utils\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * The utils package is a dependency-free helper library and ships no
     * service provider of its own, so there is nothing to register here.
     */
    protected function getPackageProviders($app): array
    {
        return [];
    }
}
