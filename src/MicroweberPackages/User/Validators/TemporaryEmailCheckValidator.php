<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/19/2020
 * Time: 5:28 PM
 */

namespace MicroweberPackages\User\Validators;

use Illuminate\Validation\Validator;
use MicroweberPackages\Utils\ThirdPartyLibs\DisposableEmailChecker;

class TemporaryEmailCheckValidator {

    public function validate($attribute, $value, $parameters, Validator $validator) {

        $inputs = $validator->getData();

        $checker = new DisposableEmailChecker();
        $isTempEmail = $checker->check($inputs['email']);
        if ($isTempEmail) {
            return false;
        }

        return true;
    }
}