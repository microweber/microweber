<?php

namespace MicroweberPackages\Modules\TwitterFeed\Providers;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\TwitterFeed\Http\Livewire\TwitterFeedSettingsComponent;

class TwitterFeedServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-twitter-feed');
        $package->hasViews('microweber-module-twitter-feed');
    }

    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-twitter-feed::settings', TwitterFeedSettingsComponent::class);

    }

}
