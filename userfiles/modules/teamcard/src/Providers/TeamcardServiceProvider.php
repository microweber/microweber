<?php

namespace MicroweberPackages\Modules\Teamcard\Providers;

use Livewire\Livewire;
use MicroweberPackages\Modules\Teamcard\Http\Livewire\TeamcardEditItemComponent;
use MicroweberPackages\Modules\Teamcard\Http\Livewire\TeamcardListItemsComponent;
use MicroweberPackages\Modules\Teamcard\Http\Livewire\TeamcardSettingsComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class TeamcardServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-teamcard');
        $package->hasViews('microweber-module-teamcard');
    }


    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-teamcard::settings', TeamcardSettingsComponent::class);
        Livewire::component('microweber-module-teamcard::edit-item', TeamcardEditItemComponent::class);
        Livewire::component('microweber-module-teamcard::list-items', TeamcardListItemsComponent::class);

    }
}
