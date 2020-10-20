<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/19/2020
 * Time: 5:28 PM
 */

namespace MicroweberPackages\User\Validators;

use Illuminate\Validation\Validator;
use MicroweberPackages\User\TosManager;

class TermsValidator {

    public function validate($attribute, $value, $parameters, Validator $validator) {

        $isOk = true;
        $inputs = $validator->getData();

        if (!isset($inputs['email']) || empty($inputs['email'])) {
            return false;
        }

        $checks = [];
        if(is_array($parameters)) {
            foreach ($parameters as $parameter) {
                $tos = new TosManager();
                $checks[$parameter] = $tos->terms_check($parameter, $inputs['email']);
            }
        }

        var_dump($checks);

    }
}