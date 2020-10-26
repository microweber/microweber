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
       // return false;
// @todo

//        $register_email_verify = get_option('register_email_verify', 'users');
//        $registration_approval_required = get_option('registration_approval_required', 'users');
//
//
//        $found = false;
//        $inputs = $this->all();
//        if ($inputs and isset($inputs['username']) and $inputs['username']) {
//            $found = User::where('username', $inputs['username'])->first();
//        } else if ($inputs and isset($inputs['email']) and $inputs['email']) {
//            $found = User::where('email', $inputs['username'])->first();
//        }

      //  throw new AuthorizationException('This action is unauthorized.');


        //   $user =


//            if ($user_data['is_active'] == 0) {
//
//                $registration_approval_required = get_option('registration_approval_required', 'users');
//                $register_email_verify = get_option('register_email_verify', 'users');
//                if ($registration_approval_required == 'y') {
//                    return array('error' => 'Your account is awaiting approval');
//                } elseif ($user_data['is_verified'] != 1 && $register_email_verify == 'y') {
//                    return array('error' => 'Please verify your email address. Please check your inbox for your account activation email');
//                } else {
//                    return array('error' => 'Your account has been disabled');
//                }
//            }


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


        if (get_option('captcha_disabled', 'users') !== 'y') {
            $rules['captcha'] = 'required|min:1|captcha';
        }

        $rules['password'] = 'required|min:1';

        return $rules;
    }
}
