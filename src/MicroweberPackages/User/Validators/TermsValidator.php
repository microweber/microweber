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

        $inputs = $validator->getData();

        if (!isset($inputs['email']) || empty($inputs['email'])) {
            return false;
        }

        $isOk = true;
        $checksDb = [];
        if(is_array($parameters)) {
            foreach ($parameters as $parameter) {
                $tos = new TosManager();
                $checksDb[$parameter] = $tos->terms_check($parameter, $inputs['email']);
                if (!$checksDb[$parameter]) {
                    $isOk = false;
                }
            }
        }

        if ($isOk) {
            return true;
        }

        if (!isset($inputs['terms'])) {
            return false;
        }

        if (isset($inputs['terms']) && $inputs['terms'] == '1') {
            foreach ($checksDb as $checkTermName=>$checkTermData) {
                $tos = new TosManager();
                $tos->terms_accept($checkTermName, $inputs['email']);
            }
        }

        return true;
    }
}