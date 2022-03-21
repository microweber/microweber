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

namespace MicroweberPackages\Helper;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use function Clue\StreamFilter\fun;


class HelpersServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        /**
         * @property \MicroweberPackages\Helper\Format    $format
         */
        $this->app->singleton('format', function () {
            return new Format();
        });

		/**
         * @property \MicroweberPackages\Helper\XSSSecurity    $xss_security
         */
        $this->app->singleton('xss_security', function () {
            return new XSSSecurity();
        });

        $this->app->singleton('html_clean', function () {
            return new HTMLClean();
        });


        /**
         * @property \MicroweberPackages\Helper\UrlManager    $url_manager
         */
        $this->app->singleton('url_manager', function () {
            return new UrlManager();
        });

        if (is_cli()) {
            Route::get('uri_test_details', function () {
                return app()->url_manager->current();
            })->name('uri_test_details');
        }
    }
}
