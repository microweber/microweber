<?php

namespace MicroweberPackages\Modules\TweetEmbed\Providers;

use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\TweetEmbed\Http\Livewire\TweetEmbedSettingsComponent;

class TweetEmbedServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-tweet-embed');
        $package->hasViews('microweber-module-tweet-embed');
    }

    public function register(): void
    {
        parent::register();
        ModuleAdmin::registerSettingsComponent('tweet_embed', TweetEmbedSettingsComponent::class);

    }

}
