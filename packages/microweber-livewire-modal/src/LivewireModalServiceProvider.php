<?php

declare(strict_types=1);

namespace MicroweberPackages\LivewireModal;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * Registers the Livewire modal stack for standalone Laravel apps and the CMS.
 *
 * Component aliases (for drop-in compatibility):
 * - microweber-livewire-modal (canonical)
 * - livewire-ui-modal (legacy wire-elements / Microweber)
 * - wire-elements-modal (legacy)
 */
class LivewireModalServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('livewire-modal')
            ->hasConfigFile('livewire-modal')
            ->hasViews('livewire-modal');
    }

    public function packageBooted(): void
    {
        Livewire::component('microweber-livewire-modal', Modal::class);
        Livewire::component('livewire-ui-modal', Modal::class);
        Livewire::component('wire-elements-modal', Modal::class);
    }
}
