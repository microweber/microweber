# Register module

Create a service provider, and register the module in the Filament registry.
 
 
```php
<?php 

use Filament\Facades\Filament;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class TodoModuleServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-todo');
    }

    public function register(): void
    {
        parent::register();

        FilamentRegistry::registerPage(TodoModuleSettings::class);

    }
    
    public function boot(): void
    {
        parent::boot();
        Filament::serving(function () {
            $panelId = Filament::getCurrentPanel()->getId();
            if ($panelId == 'admin') {
                ModuleAdmin::registerLiveEditSettingsUrl('todo', TodoModuleSettings::getUrl());
            }
        });

    }
}
```


```php
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

```