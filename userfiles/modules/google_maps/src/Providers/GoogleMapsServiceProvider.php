<?php


namespace MicroweberPackages\Modules\GoogleMaps\Providers;


use MicroweberPackages\Modules\GoogleMaps\Filament\GoogleMapsModuleSettings;
use MicroweberPackages\Modules\GoogleMaps\Http\Livewire\GoogleMapsViewComponent;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use MicroweberPackages\Package\ModulePackage;
use Spatie\LaravelPackageTools\Package;


class GoogleMapsServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-google-maps');
        $package->hasViews('microweber-module-google-maps');
    }

    public function configureModule(ModulePackage $module): void
    {
        $module->type('google_maps');
        $module->hasLiveEditSettings(GoogleMapsModuleSettings::class);
        $module->hasViewComponent(GoogleMapsViewComponent::class);
    }

}
