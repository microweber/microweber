<?php

namespace MicroweberPackages\Modules\Layouts\Providers;


use Filament\Facades\Filament;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Layouts\Filament\LayoutsModuleSettingsPage;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LayoutsModuleServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-layouts');
        $package->hasViews('microweber-module-layouts');
    }

    public function register(): void
    {
        parent::register();
        FilamentRegistry::registerPage(LayoutsModuleSettingsPage::class);

    }


    public function boot(): void
    {
        parent::boot();
        Filament::serving(function () {
            $panelId = Filament::getCurrentPanel()->getId();
            if ($panelId == 'admin') {
                ModuleAdmin::registerLiveEditSettingsUrl('layouts', LayoutsModuleSettingsPage::getUrl());
            }
        });

    }
}
