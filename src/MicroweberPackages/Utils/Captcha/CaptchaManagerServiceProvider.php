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

namespace MicroweberPackages\Utils\Captcha;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;


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
         * @property \MicroweberPackages\Utils\Captcha\CaptchaManager $captcha_manager
         */
        $this->app->singleton('captcha_manager', function ($app) {
            return new CaptchaManager();
        });

        Validator::extendImplicit('captcha', 'MicroweberPackages\Utils\Captcha\Validators\CaptchaValidator@validate', __('Invalid captcha answer!'));

    }
}
