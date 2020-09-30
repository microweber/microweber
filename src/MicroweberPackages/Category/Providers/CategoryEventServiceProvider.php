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
use MicroweberPackages\Category\Listeners\AddCategoryListener;
use MicroweberPackages\Category\Listeners\EditCategoryListener;
use MicroweberPackages\Product\Events\ProductWasCreated;
use MicroweberPackages\Product\Events\ProductWasUpdated;

class CategoryEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        ProductWasCreated::class => [
            AddCategoryListener::class
        ],
        ProductWasUpdated::class => [
            EditCategoryListener::class
        ]
    ];
}

