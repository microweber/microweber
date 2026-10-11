<?php

namespace MicroweberPackages\User\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use MicroweberPackages\User\Filament\Resources\UsersResource;


class UsersFilamentPlugin implements Plugin
{

    public function getId(): string
    {
        return 'users';
    }

    public function register(Panel $panel): void
    {
         $panel->resources([
            UsersResource::class
         ]);

    }

    public function boot(Panel $panel): void
    {

    }

}
