<?php


namespace MicroweberPackages\Modules\GoogleMaps\Providers;


use Filament\Facades\Filament;
use Livewire\Livewire;

use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\GoogleMaps\Filament\GoogleMapsModuleSettings;
use MicroweberPackages\Modules\GoogleMaps\Http\Livewire\GoogleMapsSettingsComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class GoogleMapsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-google-maps');
        $package->hasViews('microweber-module-google-maps');
    }

    public function register(): void
    {

        parent::register();

        FilamentRegistry::registerPage(GoogleMapsModuleSettings::class);



    }

    public function boot(): void
    {
        Filament::serving(function () {
            $panelId = Filament::getCurrentPanel()->getId();
            if ($panelId == 'admin') {
                ModuleAdmin::registerLiveEditSettingsUrl('google_maps', GoogleMapsModuleSettings::getUrl());
            }
        });

    }

}
