<?php

namespace MicroweberPackages\FilamentRegistry\Tests\Standalone\Fixtures;

/**
 * Fake Filament cluster class for standalone testing.
 */
class FakeCluster
{
    public static function getNavigationLabel(): string
    {
        return 'Fake Cluster';
    }
}