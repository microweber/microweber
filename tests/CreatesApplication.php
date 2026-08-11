<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

/**
 * @deprecated Prefer extending \Tests\TestCase (Orchestra Testbench).
 *
 * Kept for dual-mode package TestCases that detect monorepo installs via
 * `trait_exists(\Tests\CreatesApplication::class)` and for DuskTestCase
 * until those are fully migrated onto Testbench.
 */
trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
