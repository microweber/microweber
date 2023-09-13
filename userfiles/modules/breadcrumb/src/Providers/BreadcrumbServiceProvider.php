<?php

namespace MicroweberPackages\Modules\Breadcrumb\Providers;

use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\Breadcrumb\Http\Livewire\BreadcrumbSettingsComponent;

class BreadcrumbServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-breadcrumb');
        $package->hasViews('microweber-module-breadcrumb');
    }

    public function register(): void
    {
        parent::register();
        ModuleAdmin::registerSettingsComponent('breadcrumb', BreadcrumbSettingsComponent::class);

    }

}
