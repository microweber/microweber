<?php

namespace MicroweberPackages\Modules\Posts\Providers;

use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\Posts\Http\Livewire\PostsSettingsComponent;

class PostsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-posts');
        $package->hasViews('microweber-module-posts');
    }

    public function register(): void
    {
        parent::register();
        ModuleAdmin::registerSettingsComponent('posts', PostsSettingsComponent::class);

    }

}
