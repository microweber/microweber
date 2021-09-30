<?php

namespace MicroweberPackages\Multilanguage\Listeners;

use Illuminate\Foundation\Events\LocaleUpdated;

class LocaleUpdatedListener
{
    public function handle(LocaleUpdated $event)
    {
        app()->lang_helper->clearCache();
        app()->permalink_manager->clearCache();
//   app()->content_manager->clearCache();
//        app()->content_repository->clearCache();
//        app()->category_repository->clearCache();
//        app()->option_repository->clearCache();
//        app()->menu_repository->clearCache();
//        app()->multilanguage_repository->clearCache();
    }
}
