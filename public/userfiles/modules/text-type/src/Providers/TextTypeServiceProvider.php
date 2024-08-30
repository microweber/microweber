<?php

namespace MicroweberPackages\Modules\TextType\Providers;

use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\TextType\Http\Livewire\TextTypeSettingsComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class TextTypeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-text-type');
        $package->hasViews('microweber-module-text-type');
    }

    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-text-type::settings', TextTypeSettingsComponent::class);

        ModuleAdmin::registerSettings('text-type', 'microweber-module-text-type::settings');
    }
}
