<?php

namespace MicroweberPackages\User\Providers;

use Filament\PluginServiceProvider;
use MicroweberPackages\User\Filament\Resources\UserResource;
use Spatie\LaravelPackageTools\Package;

class UserFilamentServiceProvider extends PluginServiceProvider
{
    public static string $name = 'user';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews();

    }

    public function packageRegistered(): void
    {
        parent::packageRegistered();

    }

    public function packageBooted(): void
    {
        parent::packageBooted();


    }

    protected function getResources(): array
    {

        return [
            UserResource::class,
        ];
    }

    protected function getStyles(): array
    {
        return [

        ];
    }

    protected function getBeforeCoreScripts(): array
    {
        return [

        ];
    }
}
