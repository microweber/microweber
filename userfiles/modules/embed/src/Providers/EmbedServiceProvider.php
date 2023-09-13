<?php

namespace MicroweberPackages\Modules\Embed\Providers;

use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\Embed\Http\Livewire\EmbedSettingsComponent;

class EmbedServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-embed');
        $package->hasViews('microweber-module-embed');
    }

    public function register(): void
    {
        parent::register();
        ModuleAdmin::registerSettingsComponent('embed', EmbedSettingsComponent::class);

    }

}
