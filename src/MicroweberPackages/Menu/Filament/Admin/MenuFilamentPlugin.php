<?php

namespace MicroweberPackages\Menu\Filament\Admin;

use Filament\Contracts\Plugin;

use Filament\Panel;
use MicroweberPackages\Menu\Filament\Admin\Pages\AdminMenusPage;

class MenuFilamentPlugin implements Plugin
{

    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'menus';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([

            ])
            ->pages([
                AdminMenusPage::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
