<?php

namespace MicroweberPackages\User\Providers;

use Filament\PluginServiceProvider;
use MicroweberPackages\User\Filament\Pages\Profile;
use MicroweberPackages\User\Filament\Resources\UserResource;
use MicroweberPackages\User\Filament\Widgets\UsersStatsWidget;
use Spatie\LaravelPackageTools\Package;

class UserFilamentServiceProvider extends PluginServiceProvider
{
    public static string $name = 'user';

    protected function getWidgets(): array
    {
        return [
            UsersStatsWidget::class
        ];
    }

    protected function getResources(): array
    {

        return [
            UserResource::class,
        ];
    }

    protected function getPages(): array
    {
        return [
            Profile::class
        ];
    }


}
