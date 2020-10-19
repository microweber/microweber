<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/19/2020
 * Time: 5:28 PM
 */

namespace MicroweberPackages\User\Validators;

use Illuminate\Validation\Validator;

class TermsValidator {

    public function validate($attribute, $value, $parameters, Validator $validator) {

        $isOk = true;
        $inputs = $validator->getData();

        if (!isset($inputs['email']) || empty($inputs['email'])) {
            return false;

        }
        return false;

        /* if(is_array($parameters)) {
            foreach ($parameters as $parameter) {
                $tos = new TosManager();
                return $tos->terms_check($parameter, $user_id_or_email);
            }
        }
        */

       var_dump($parameters);

        var_dump($validator->getData());

       // die();

    }
}