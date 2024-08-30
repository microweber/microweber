<?php

namespace MicroweberPackages\Modules\SocialLinks\Providers;

use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\SocialLinks\Http\Livewire\SocialLinksSettingsComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class SocialLinksServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-social-links');
        $package->hasViews('microweber-module-social-links');
    }


    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-social-links::settings', SocialLinksSettingsComponent::class);
        ModuleAdmin::registerSettings('social_links', 'microweber-module-social-links::settings');

    }
}
