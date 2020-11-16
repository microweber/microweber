<?php

namespace MicroweberPackages\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use MicroweberPackages\User\Models\User;
use \Illuminate\Auth\Access\AuthorizationException;

class LoginRequest extends FormRequest
{

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;



    }


    /**
     * Get the validation rules that apply to the request.s
     *
     * @return array
     */

    public function rules()
    {
        $rules = [];
        $inputs = $this->all();

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
            $rules['email'] = 'email|string|min:3|required|string|max:255';
        }

        if ($validateUsername) {
            $rules['username'] = 'string|min:1|required|string|max:255';
        }


       if (is_module('captcha') and get_option('login_captcha_enabled', 'users') === 'y') {
            $rules['captcha'] = 'captcha';
        }

        $rules['password'] = 'required|min:1';

        return $rules;
    }
}
