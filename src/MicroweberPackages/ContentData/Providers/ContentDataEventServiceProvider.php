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

namespace MicroweberPackages\ContentData\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use MicroweberPackages\ContentData\Listeners\AddContentDataProductListener;
use MicroweberPackages\ContentData\Listeners\EditContentDataProductListener;
use MicroweberPackages\Product\Events\ContentWasCreated;
use MicroweberPackages\Product\Events\ContentWasUpdated;

class ContentDataEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        ContentWasCreated::class => [
            AddContentDataProductListener::class
        ],
        ContentWasUpdated::class => [
            EditContentDataProductListener::class
        ]
    ];
}

