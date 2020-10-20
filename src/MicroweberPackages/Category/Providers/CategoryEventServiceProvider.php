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
use MicroweberPackages\Post\Events\PostWasCreated;
use MicroweberPackages\Post\Events\PostWasUpdated;
use MicroweberPackages\Product\Events\ContentWasCreated;
use MicroweberPackages\Product\Events\ContentWasUpdated;

class CategoryEventServiceProvider extends EventServiceProvider
{

    /**
     * @deprecated

        protected $listen = [
        ContentWasCreated::class => [
            AddCategoryListener::class
        ],
        ContentWasUpdated::class => [
            EditCategoryListener::class
        ],
        PostWasCreated::class => [
            AddCategoryListener::class
        ],
        PostWasUpdated::class => [
            EditCategoryListener::class
        ],
    ];*/

}

