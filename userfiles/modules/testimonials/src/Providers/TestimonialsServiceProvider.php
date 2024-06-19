<?php

namespace MicroweberPackages\Modules\Testimonials\Providers;

use Filament\Events\ServingFilament;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
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
//        Livewire::component('microweber-module-testimonials::settings', TestimonialsSettingsComponent::class);
//        Livewire::component('microweber-module-testimonials::projects-dropdown', TestimonialsProjectsDropdownComponent::class);
//
//        ModuleAdmin::registerSettings('testimonials', 'microweber-module-testimonials::settings');
        Event::listen(ServingFilament::class, function () {
            ModuleAdmin::registerAdminUrl('testimonials', admin_url('testimonials-module-settings'));
        });

        ModuleAdmin::registerLiveEditSettingsUrl('testimonials', site_url('admin-live-edit/testimonials-module-settings'));
        ModuleAdmin::registerLiveEditPanelPage(\MicroweberPackages\Modules\Testimonials\Http\Livewire\TestimonialsModuleSettings::class);

    }

}
