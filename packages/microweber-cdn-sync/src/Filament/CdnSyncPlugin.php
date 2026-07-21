<?php

namespace MicroweberPackages\CdnSync\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use MicroweberPackages\CdnSync\Filament\Pages\CdnSyncSettings;

class CdnSyncPlugin implements Plugin
{
    public function getId(): string
    {
        return 'cdn-sync';
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
            CdnSyncSettings::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}