<?php

namespace MicroweberPackages\Modules\CallToAction\Providers;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\CallToAction\Http\Livewire\CallToActionSettingsComponent;

class CallToActionServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-call-to-action');
        $package->hasViews('microweber-module-call-to-action');
    }

    public function register(): void
    {
        parent::register();
        Livewire::component('microweber-module-call-to-action::settings', CallToActionSettingsComponent::class);
        ModuleAdmin::registerSettings('call_to_action', 'microweber-module-call-to-action::settings');

    }

}
