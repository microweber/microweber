<?php

namespace MicroweberPackages\Modules\Toc\Providers;

use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Toc\Http\Livewire\TocSettingsComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class TocServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-toc');
        $package->hasViews('microweber-module-toc');
    }


    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-toc::settings', TocSettingsComponent::class);
        ModuleAdmin::registerSettings('toc', 'microweber-module-toc::settings');

    }
}
