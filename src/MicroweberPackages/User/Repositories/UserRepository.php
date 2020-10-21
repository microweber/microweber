<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/21/2020
 * Time: 3:09 PM
 */

namespace MicroweberPackages\User\Repositories;

use MicroweberPackages\User\Http\Requests\RegisterRequest;

class UserRepository {

    public function register($data)
    {
        $request = new RegisterRequest();

        $rules = $request->rules();

        $validate = \Validator::make($data, $rules);
        $validate->validate();

        return User::create($data);
    }

}