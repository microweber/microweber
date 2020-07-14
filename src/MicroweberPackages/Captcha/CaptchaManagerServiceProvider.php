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

namespace MicroweberPackages\Captcha;

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
         * @property \MicroweberPackages\Captcha\CaptchaManager    $captcha_manager
         */
        $this->app->singleton('captcha_manager', function ($app) {
            return new CaptchaManager();
        });

    }
}
