<?php

namespace MicroweberPackages\Modules\Menu\Filament\Admin;

use Filament\Contracts\Plugin;

use Filament\Panel;
use MicroweberPackages\Modules\Menu\Filament\Admin\Pages\AdminMenusPage;

class MenuFilamentPlugin implements Plugin
{

    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'menu-module';
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
