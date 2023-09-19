<?php

namespace MicroweberPackages\Modules\CustomFields\Providers;

use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\CustomFields\Http\Livewire\CustomFieldsSettingsComponent;

class CustomFieldsServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-custom-fields');
        $package->hasViews('microweber-module-custom-fields');
    }

    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-custom-fields::settings', CustomFieldsSettingsComponent::class);
        ModuleAdmin::registerSettings('custom_fields', 'microweber-module-custom-fields::settings');

    }

}
