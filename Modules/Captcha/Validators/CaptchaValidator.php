<?php



namespace Modules\Captcha\Validators;

class CaptchaValidator
{
    public function validate($attribute, $value, $parameters)
    {
        return app()->captcha_manager->validate($value);
    }
}
