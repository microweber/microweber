<?php

namespace MicroweberPackages\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use MicroweberPackages\User\Http\Requests\LoginRequest;
use MicroweberPackages\User\User;
use MicroweberPackages\Utils\ThirdPartyLibs\DisposableEmailChecker;

class UserRegisterController extends Controller
{
    public $middleware = [
        [
            'middleware'=>'xss',
            'options'=>[]
        ]
    ];

    /**
     * register api
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        if (get_option('enable_user_registration', 'users') !== 'y') {
            return array('error' => 'User registration is disabled.');
        }

        $validator = \Validator::make($request->all(), $this->rules($request->all()));
        $validator->validate();

        return User::create($request->all());
    }

    private function rules($inputs)
    {
        $rules = [];

        $validateEmail = false;
        $validateUsername = false;

        if (!isset($inputs['username']) || !isset($inputs['email'])) {
            $validateUsername = true;
        }

        if (isset($inputs['email']) && !isset($inputs['username'])) {
            $validateUsername = false;
            $validateEmail = true;
        }

        if (isset($inputs['email']) && isset($inputs['username'])) {
            $validateUsername = true;
            $validateEmail = true;
        }

        if ($validateEmail) {
            $rules['email'] = 'required|string|max:255|unique:users';
        }
        
        if ($validateUsername) {
            $rules['username'] = 'required|string|max:255|unique:users';
        }

        if (isset($inputs['confirm_password'])) {
            $rules['confirm_password'] = 'required|min:1|same:password';
        }

        // Captcha disabled
        if (get_option('captcha_disabled', 'users') !== 'y') {
            $rules['captcha'] = 'required|min:1|captcha';
        }

        // Check temporary email
        if ($inputs['email'] != false && ((get_option('disable_registration_with_temporary_email', 'users') == 'y'))) {
            $rules['email'] = $rules['email'] . '|temporary_email_check';
        }

        // Check terms is activated
        if (get_option('require_terms', 'users') == 'y') {
            $rules['terms'] = 'terms:terms_user';
            if (isset($inputs['newsletter_subscribe']) and $inputs['newsletter_subscribe']) {
                $rules['terms'] = $rules['terms'] . ', terms_newsletter';
            }
        }

        // Default requirements
        $rules['password'] = 'required|min:1';

        return $rules;
    }
}