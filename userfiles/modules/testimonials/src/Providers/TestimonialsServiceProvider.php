<?php

namespace MicroweberPackages\Modules\Testimonials\Providers;

use Livewire\Livewire;
use MicroweberPackages\Modules\Testimonials\Http\Livewire\TestimonialsProjectsDropdownComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\Testimonials\Http\Livewire\TestimonialsSettingsComponent;

class TestimonialsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-testimonials');
        $package->hasViews('microweber-module-testimonials');
    }

    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-testimonials::settings', TestimonialsSettingsComponent::class);
        Livewire::component('microweber-module-testimonials::projects-dropdown', TestimonialsProjectsDropdownComponent::class);

    }

}
