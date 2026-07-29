<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use MicroweberPackages\TemplateFonts\Filament\Pages\TemplateFontsSettings;
use MicroweberPackages\TemplateFonts\Filament\Resources\TemplateFontResource;

class TemplateFontsPlugin implements Plugin
{
    public function getId(): string
    {
        return 'template-fonts';
    }

    public static function make(): static
    {
        /** @var static $instance */
        $instance = new static(); // @phpstan-ignore new.static

        return $instance;
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                TemplateFontResource::class,
            ])
            ->pages([
                TemplateFontsSettings::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
