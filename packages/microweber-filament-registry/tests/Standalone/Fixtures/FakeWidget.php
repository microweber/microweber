<?php

namespace MicroweberPackages\FilamentRegistry\Tests\Standalone\Fixtures;

/**
 * Fake Filament widget class for standalone testing.
 */
class FakeWidget
{
    public static function getHeading(): string
    {
        return 'Fake Widget';
    }
}