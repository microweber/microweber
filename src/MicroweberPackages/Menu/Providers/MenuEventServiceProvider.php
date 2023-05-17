<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Menu\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use MicroweberPackages\Menu\Listeners\AddMenuPageListener;
use MicroweberPackages\Menu\Listeners\EditMenuPageListener;
use MicroweberPackages\Menu\Events\MenuWasCreated;
use MicroweberPackages\Menu\Events\MenuWasUpdated;

class MenuEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        MenuWasCreated::class => [
            AddMenuPageListener::class
        ],
        MenuWasUpdated::class => [
            EditMenuPageListener::class
        ]
    ];
}

