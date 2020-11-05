<?php

namespace MicroweberPackages\User\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use MicroweberPackages\User\Events\UserWasRegistered;
use MicroweberPackages\User\Http\Requests\RegisterRequest;
use MicroweberPackages\User\Models\User;

class UserRegisterController extends Controller
{
    public $middleware = [
        [
            'middleware' => 'xss',
            'options' => []
        ]
    ];

    public $fillable = [
        'username',
        'password',
        'email',
        'basic_mode',
        'first_name',
        'last_name',
        'thumbnail',
        'parent_id',
        'user_information',
        'subscr_id',
        'profile_url',
        'website_url',
        'phone'
    ];

    /**
     * register api
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        $userData = [];
        $inputs = $request->all();
        if ($inputs) {
            foreach ($inputs as $input_key => $input) {
                if (in_array($input_key, $this->fillable)) {
                    $userData[$input_key] = $input;
                }
            }
        }

        $created = User::create($userData);
        if ($created) {
            event(new Registered($created));
           // event(new UserWasRegistered($created, $request->all()));
        }


        return new \MicroweberPackages\User\Http\Resources\UserResource($created);




    }
}