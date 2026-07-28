<?php

declare(strict_types=1);

namespace MicroweberPackages\Minifier\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use MicroweberPackages\Minifier\Filament\Pages\MinifierSettings;

class MinifierPlugin implements Plugin
{
    public function getId(): string
    {
        return 'minifier';
    }

    public static function make(): static
    {
        /** @var static $instance */
        $instance = new static(); // @phpstan-ignore new.static

        return $instance;
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            MinifierSettings::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
