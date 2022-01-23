<?php

namespace MicroweberPackages\Repository\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;

use MicroweberPackages\Content\Events\ContentWasCreated;
use MicroweberPackages\Content\Events\ContentWasUpdated;

class RepositoryEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        ContentWasCreated::class => [
        //    AddContentDataProductListener::class
        ],
        ContentWasUpdated::class => [
        //    EditContentDataProductListener::class
        ]
    ];
}

