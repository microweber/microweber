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

namespace Modules\CustomFields\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use MicroweberPackages\Content\Events\ContentWasCreated;
use MicroweberPackages\Content\Events\ContentWasUpdated;
use Modules\CustomFields\Listeners\AddCustomFieldProductListener;
use Modules\CustomFields\Listeners\EditCustomFieldProductListener;

class CustomFieldsEventServiceProvider extends EventServiceProvider
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

