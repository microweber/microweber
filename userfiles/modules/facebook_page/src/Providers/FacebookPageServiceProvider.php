<?php

namespace MicroweberPackages\Modules\FacebookPage\Providers;

use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\FacebookPage\Http\Livewire\FacebookPageSettingsComponent;

class FacebookPageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-facebook-page');
        $package->hasViews('microweber-module-facebook-page');
    }

    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-facebook-page::settings', FacebookPageSettingsComponent::class);
        ModuleAdmin::registerSettings('facebook_page', 'microweber-module-facebook-page::settings');

    }

}
