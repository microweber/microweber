<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/19/2020
 * Time: 5:28 PM
 */

namespace MicroweberPackages\Utils\Captcha\Validators;

use Illuminate\Validation\Validator;

class CaptchaValidator {

    public function validate($attribute, $value, $parameters, Validator $validator) {
        return app()->captcha_manager->validate($value);
    }

}