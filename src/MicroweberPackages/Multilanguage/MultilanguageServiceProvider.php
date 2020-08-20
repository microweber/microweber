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

namespace MicroweberPackages\Multilanguage;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Multilanguage\Observers\MultilanguageObserver;
use MicroweberPackages\Page\Page;

class MultilanguageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        Page::observe(MultilanguageObserver::class);

        $this->loadMigrationsFrom(__DIR__ . '/migrations/');
    }
}
