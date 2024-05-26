<?php

namespace MicroweberPackages\Livewire;

use Livewire\Livewire;
use LivewireUI\Modal\LivewireModalServiceProvider;
use LivewireUI\Modal\Modal;
use Spatie\LaravelPackageTools\Package;

class LivewireMwModalServiceProvider extends LivewireModalServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('livewire-mw-modal');
    }

    public function bootingPackage(): void
    {
        Livewire::component('livewire-ui-modal', MwModal::class);
    }

}
