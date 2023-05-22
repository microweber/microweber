<?php
namespace MicroweberPackages\Modules\Comments;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class CommentsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-comments');
        $package->hasViews('microweber-module-comments');
    }

    public function register(): void
    {
        parent::register();
//      Livewire::component('microweber-module-btn::live-edit', ButtonSettingsComponent::class);

    }

}
