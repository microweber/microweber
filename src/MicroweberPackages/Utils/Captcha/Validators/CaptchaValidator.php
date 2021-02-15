<?php



namespace MicroweberPackages\Utils\Captcha\Validators;

use Illuminate\Validation\Validator;

class CaptchaValidator extends Validator
{


    public function validateCaptcha($attribute, $value, $parameters)
    {
        return app()->captcha_manager->validate($value);
    }


    protected function replaceCaptcha($key)
    {
        return _e('Invalid captcha answer!', true);
    }

}