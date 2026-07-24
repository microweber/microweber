<?php

declare(strict_types=1);

namespace MicroweberPackages\ImageOptimization\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use MicroweberPackages\ImageOptimization\Filament\Pages\ImageOptimizationSettings;

class ImageOptimizationPlugin implements Plugin
{
    public function getId(): string
    {
        return 'image-optimization';
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
            ImageOptimizationSettings::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
