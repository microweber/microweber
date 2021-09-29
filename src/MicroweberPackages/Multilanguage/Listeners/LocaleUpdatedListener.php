<?php

namespace MicroweberPackages\Multilanguage\Listeners;

use Illuminate\Foundation\Events\LocaleUpdated;

class LocaleUpdatedListener
{
    public function handle(LocaleUpdated $event)
    {
        app()->lang_helper->clearCache();
        app()->permalink_manager->clearCache();
        app()->content_manager->clearCache();
    }
}
