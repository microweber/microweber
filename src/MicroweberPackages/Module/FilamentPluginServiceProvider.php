<?php

namespace MicroweberPackages\Module;


use Filament\PluginServiceProvider;
use MicroweberPackages\Module\Filament\Resources\ModuleResource;
use Spatie\LaravelPackageTools\Package;

class FilamentPluginServiceProvider extends PluginServiceProvider
{
    public static string $name = 'billing';

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
            ModuleResource::class,
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
