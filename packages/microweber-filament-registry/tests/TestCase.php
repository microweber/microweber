<?php

namespace MicroweberPackages\FilamentRegistry\Tests;

use MicroweberPackages\FilamentRegistry\FilamentRegistryServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            FilamentRegistryServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'FilamentRegistry' => \MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry::class,
        ];
    }
}