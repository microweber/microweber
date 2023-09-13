<?php

namespace MicroweberPackages\Modules\Accordion\Providers;

use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Accordion\Http\Livewire\AccordeonSettingsComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class AccordionServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-accordion');
        $package->hasViews('microweber-module-accordion');
    }


    public function register(): void
    {
        parent::register();
        ModuleAdmin::registerSettingsComponent('accordion', AccordeonSettingsComponent::class);



    }
}
