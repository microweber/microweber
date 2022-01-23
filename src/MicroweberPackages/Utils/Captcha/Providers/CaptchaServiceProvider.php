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
use MicroweberPackages\Utils\Captcha\Validators\CaptchaValidator;


class CaptchaServiceProvider extends ServiceProvider
{

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

        \Validator::extendImplicit('captcha', CaptchaValidator::class.'@validate', 'Invalid captcha answer!');



    }
}
