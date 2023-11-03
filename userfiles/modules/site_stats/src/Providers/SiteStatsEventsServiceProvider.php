<?php

namespace MicroweberPackages\Modules\SiteStats\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\Cart\Events\AddToCartEvent;
use MicroweberPackages\Cart\Events\RemoveFromCartEvent;
use MicroweberPackages\Modules\SiteStats\Listeners\AddToCartListener;
use MicroweberPackages\Modules\SiteStats\Listeners\RemoveFromCartListener;
use MicroweberPackages\Modules\SiteStats\Listeners\UserWasLogged;
use MicroweberPackages\Modules\SiteStats\Listeners\UserWasRegistered;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\SiteStats\Http\Livewire\SiteStatsSettingsComponent;

class SiteStatsEventsServiceProvider extends EventServiceProvider
{
    protected $listen = [
        Login::class => [
            UserWasLogged::class
        ],
        Registered::class => [
            UserWasRegistered::class,
        ],
        AddToCartEvent::class => [
            AddToCartListener::class
        ],
        RemoveFromCartEvent::class => [
            RemoveFromCartListener::class
        ]
    ];


}
