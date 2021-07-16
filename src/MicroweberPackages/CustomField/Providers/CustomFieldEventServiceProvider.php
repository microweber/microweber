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

namespace MicroweberPackages\CustomField\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use MicroweberPackages\CustomField\Listeners\AddCustomFieldProductListener;
use MicroweberPackages\CustomField\Listeners\EditCustomFieldProductListener;
use MicroweberPackages\Content\Events\ContentWasCreated;
use MicroweberPackages\Content\Events\ContentWasUpdated;

class CustomFieldEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        ContentWasCreated::class => [
            AddCustomFieldProductListener::class
        ],
        ContentWasUpdated::class => [
            EditCustomFieldProductListener::class
        ]
    ];
}

