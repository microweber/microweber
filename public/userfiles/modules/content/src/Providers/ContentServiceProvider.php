<?php

namespace MicroweberPackages\Modules\Content\Providers;

use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\Content\Http\Livewire\ContentSettingsComponent;

class ContentServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-content');
        $package->hasViews('microweber-module-content');
    }

    public function register(): void
    {
        parent::register();
        // not ready yet
        // Livewire::component('microweber-module-content::settings', ContentSettingsComponent::class);

        //  ModuleAdmin::registerSettings('content', 'microweber-module-content::settings');

    }

}
