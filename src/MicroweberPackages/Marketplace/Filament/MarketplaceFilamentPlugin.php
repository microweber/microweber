<?php

namespace MicroweberPackages\Marketplace\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use MicroweberPackages\Marketplace\Filament\Admin\Pages\Marketplace;
use MicroweberPackages\User\Filament\Resources\UsersResource;


class MarketplaceFilamentPlugin implements Plugin
{

    public function getId(): string
    {
        return 'marketplace';
    }

    public function register(Panel $panel): void
    {

    }

    public function boot(Panel $panel): void
    {

    }

}
