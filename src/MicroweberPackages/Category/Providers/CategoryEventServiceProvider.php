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

namespace MicroweberPackages\Category\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use MicroweberPackages\Category\Events\CategoryWasCreated;
use MicroweberPackages\Category\Events\CategoryWasDeleted;
use MicroweberPackages\Category\Events\CategoryWasUpdated;
use MicroweberPackages\Category\Listeners\CategoryListener;

class CategoryEventServiceProvider extends EventServiceProvider
{

    protected $listen = [
        CategoryWasCreated::class => [
            CategoryListener::class
        ],
        CategoryWasUpdated::class => [
            CategoryListener::class
        ]
    ];

}
