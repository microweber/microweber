<?php


namespace MicroweberPackages\Utils\Captcha\Providers;


use Illuminate\Validation\ValidationServiceProvider;
use MicroweberPackages\Utils\Captcha\Validators\CaptchaValidator;

class CaptchaValidatorServiceProvider extends ValidationServiceProvider
{
    /**
     * Bootstrap any necessary services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->validator->resolver(function ($translator, $data, $rules, $messages)
        {
            return new CaptchaValidator($translator, $data, $rules, $messages);
        });

    }

}