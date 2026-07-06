<?php

declare(strict_types=1);

namespace MicroweberPackages\FilamentModalTeleport\Tests;

/**
 * Base test case for the modal-teleport package Livewire tests.
 *
 * When running inside the Microweber monorepo, extends the app's TestCase
 * so the full Filament + Livewire stack is available.
 *
 * For standalone usage (with Orchestra Testbench), swap to the Testbench
 * base class instead.
 */
abstract class TestCase extends \Tests\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Register the service provider so views resolve
        $this->app->register(\MicroweberPackages\FilamentModalTeleport\ModalTeleportServiceProvider::class);
    }
}
