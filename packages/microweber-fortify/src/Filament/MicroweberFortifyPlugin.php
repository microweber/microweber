<?php

namespace MicroweberPackages\Fortify\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use MicroweberPackages\Fortify\Filament\Pages\TwoFactorSettingsPage;

class MicroweberFortifyPlugin implements Plugin
{
    public static function make(): static
    {
        return new static();
    }

    public function getId(): string
    {
        return 'microweber-fortify';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            TwoFactorSettingsPage::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}