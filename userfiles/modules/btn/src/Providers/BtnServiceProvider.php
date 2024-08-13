<?php


namespace MicroweberPackages\Modules\Btn\Providers;


use Filament\Facades\Filament;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Btn\Filament\ButtonModuleSettings;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class BtnServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-btn');
        $package->hasViews('microweber-module-btn');
    }

    public function register(): void
    {
        parent::register();

        FilamentRegistry::registerPage(ButtonModuleSettings::class);

    }

    public function boot(): void
    {
        parent::boot();
        Filament::serving(function () {
            $panelId = Filament::getCurrentPanel()->getId();
            if ($panelId == 'admin') {
                ModuleAdmin::registerLiveEditSettingsUrl('btn', ButtonModuleSettings::getUrl());
            }
        });

    }
}
