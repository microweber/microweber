<?php

namespace MicroweberPackages\FilamentRegistry\Tests\Standalone\Fixtures;

/**
 * Fake Filament page class for standalone testing.
 */
class FakePage
{
    public static function getNavigationLabel(): string
    {
        return 'Fake Page';
    }
}