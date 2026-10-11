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

namespace MicroweberPackages\Template;




use MicroweberPackages\App\Providers\EventServiceProvider;
use MicroweberPackages\Option\Events\OptionWasCreated;
use MicroweberPackages\Option\Events\OptionWasDeleted;
use MicroweberPackages\Option\Events\OptionWasUpdated;
use MicroweberPackages\Template\Listeners\TemplateClearCachedCssListener;

class TemplateEventServiceProvider extends EventServiceProvider
{

    protected $listen = [
        OptionWasUpdated::class => [
            TemplateClearCachedCssListener::class,
        ],
        OptionWasCreated::class => [
            TemplateClearCachedCssListener::class,
        ],
        OptionWasDeleted::class => [
            TemplateClearCachedCssListener::class,
        ],
    ];
}
