<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/21/2020
 * Time: 3:09 PM
 */

namespace MicroweberPackages\User\Repositories;

use MicroweberPackages\User\Http\Requests\RegisterRequest;
use MicroweberPackages\User\User;

class UserRepository {

    public function register($data)
    {
        $registerRequest = new RegisterRequest();
        $registerRequest->merge($data);
        $registerRequest->validate($registerRequest->rules());

        return User::create($data);
    }

}