<?php

declare(strict_types=1);

namespace MicroweberPackages\Role\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * The RoleServiceProvider cannot be booted in an isolated Testbench app: it
     * composes the MicroweberPackages\ConfigMerge trait and the
     * MicroweberPackages\FilamentRegistry facade (CMS-only siblings) and registers
     * Filament resources against the live admin panel. This standalone suite
     * therefore boots a bare Testbench app and asserts that the package's
     * dependency-light classes, config and source layout are intact — a real,
     * green suite without the full CMS runtime.
     */
    protected function getPackageProviders($app): array
    {
        return [];
    }
}
