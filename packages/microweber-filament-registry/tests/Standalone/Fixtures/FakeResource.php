<?php

namespace MicroweberPackages\FilamentRegistry\Tests\Standalone\Fixtures;

/**
 * Fake Filament resource class for standalone testing.
 */
class FakeResource
{
    public static function getNavigationLabel(): string
    {
        return 'Fake Resource';
    }
}