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

namespace MicroweberPackages\Utils\Captcha\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Utils\Captcha\CaptchaManager;


class CaptchaManagerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(CaptchaValidatorServiceProvider::class);

    }
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\Utils\Captcha\CaptchaManager $captcha_manager
         */
        $this->app->singleton('captcha_manager', function ($app) {
            return new CaptchaManager();
        });

       // Validator::extendImplicit('captcha', 'MicroweberPackages\Utils\Captcha\Validators\CaptchaValidator@validate', 'Invalid captcha answer!');



    }
}
