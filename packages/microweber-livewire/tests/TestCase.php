<?php

declare(strict_types=1);

namespace MicroweberPackages\Livewire\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * The package's LivewireServiceProvider wires Livewire v4 + the host CMS
     * (config-merge, routes, synths) and cannot boot outside the integrated
     * application. These standalone tests therefore assert the package's own
     * dependency-free building blocks resolve, without registering it.
     */
    protected function getPackageProviders($app): array
    {
        return [];
    }
}
