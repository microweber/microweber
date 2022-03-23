<?php
namespace MicroweberPackages\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidatesWhenResolvedTrait;
use Illuminate\Validation\ValidationException;
use Auth;

class RegisterRequest extends FormRequest
{

    /**
     * @return bool
     */
    public function authorize()
    {
        $enable_user_gesitration = get_option('enable_user_registration', 'users');
        if ($enable_user_gesitration === 'n') {
            return false;
        }
        if(user_id()){
            //user is logged in so we will not allow him to register
            return false;
        }
        return true;
    }


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
            $rules['email'] = 'email|string|min:3|required|string|max:255|unique:users';
        }

        if ($validateUsername) {
            $rules['username'] = 'alpha_dash|string|min:1|required|string|max:255|unique:users';
        }
        if (isset($inputs['confirm_password'])) {
            $rules['confirm_password'] = 'required|min:1|same:password';
        }

        if (get_option('captcha_disabled', 'users') !== 'y') {
            $rules['captcha'] = 'captcha';
        }

        if (isset($inputs['email']) && $inputs['email'] != false && ((get_option('disable_registration_with_temporary_email', 'users') == 'y'))) {
            $rules['email'] = $rules['email'] . '|temporary_email_check';
        }

        if (get_option('require_terms', 'users') == 'y') {
            $rules['terms'] = 'terms:terms_user';
            if (isset($inputs['newsletter_subscribe']) and $inputs['newsletter_subscribe']) {
                $rules['terms'] = $rules['terms'] . ', terms_newsletter';
            }
        }
        $rules['password'] = 'required|min:1|max:500';


        return $rules;
    }
}
