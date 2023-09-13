<?php

namespace MicroweberPackages\Modules\FacebookLike\Providers;

use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\FacebookLike\Http\Livewire\FacebookLikeSettingsComponent;

class FacebookLikeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-facebook-like');
        $package->hasViews('microweber-module-facebook-like');
    }

    public function register(): void
    {
        parent::register();

         ModuleAdmin::registerSettingsComponent('facebook_like', FacebookLikeSettingsComponent::class);
    }

}
