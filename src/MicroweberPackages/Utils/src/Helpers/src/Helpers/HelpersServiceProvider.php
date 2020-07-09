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

namespace MicroweberPackages\Helpers;

use Illuminate\Support\ServiceProvider;


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
         * @property \MicroweberPackages\Helpers\Format    $format
         */
        $this->app->singleton('format', function () {
            return new Format();
        });

		/**
         * @property \MicroweberPackages\Helpers\XSSSecurity    $xss_security
         */
        $this->app->singleton('xss_security', function () {
            return new XSSSecurity();
        });

        /**
         * @property \MicroweberPackages\Helpers\UrlManager    $url_manager
         */
        $this->app->singleton('url_manager', function () {
            return new UrlManager();
        });
    }
}
