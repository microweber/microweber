<?php

namespace MicroweberPackages\Fortify\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use MicroweberPackages\Fortify\Filament\Pages\TwoFactorSettingsPage;

class MicroweberFortifyPlugin implements Plugin
{
    protected bool $showNavigation = true;

    public static function make(): static
    {
        return new static(); // @phpstan-ignore new.static
    }

    public function getId(): string
    {
        return 'microweber-fortify';
    }

    /**
     * Hide the 2FA settings page from the sidebar navigation.
     */
    public function navigationHidden(bool $hidden = true): static
    {
        $this->showNavigation = !$hidden;
        return $this;
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

    public function shouldShowNavigation(): bool
    {
        return $this->showNavigation;
    }
}