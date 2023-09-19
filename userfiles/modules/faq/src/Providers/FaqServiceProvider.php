<?php

namespace MicroweberPackages\Modules\Faq\Providers;

use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\Faq\Http\Livewire\FaqSettingsComponent;

class FaqServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-faq');
        $package->hasViews('microweber-module-faq');
    }

    public function register(): void
    {
        parent::register();
        Livewire::component('microweber-module-faq::settings', FaqSettingsComponent::class);
        ModuleAdmin::registerSettings('faq', 'microweber-module-faq::settings');

    }

}
