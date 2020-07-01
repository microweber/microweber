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

namespace MicroweberPackages\CaptchaManager;

use Illuminate\Support\ServiceProvider;


class CaptchaManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\CaptchaManager\CaptchaManager    $captcha_manager
         */
        $this->app->singleton('captcha_manager', function ($app) {
            return new CaptchaManager();
        });

    }
}
