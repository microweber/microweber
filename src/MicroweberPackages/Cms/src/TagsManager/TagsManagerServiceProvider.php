<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber LTD
 *
 * For full license information see
 * http://Microweber.com/license/
 *
 */

namespace MicroweberPackages\TagsManager;

use Illuminate\Support\ServiceProvider;

class TagsManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\TagsManager\TagsManager    $tags_manager
         */
        $this->app->singleton('tags_manager', function ($app) {
            return new TagsManager();
        });
    }
}