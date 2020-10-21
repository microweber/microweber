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

class RegisterRequest extends FormRequest
{



    /**
     * @return bool
     */
    public function authorize()
    {

        $enable_user_gesitration = get_option('enable_user_registration', 'users');
        if ($enable_user_gesitration == 'n') {
            return false;
        }


        return true;
    }

    /**
     * Get the validation rules that apply to the request.s
     *
     * @return array
     */
    public function rules()
    {
        $this->_registerRules['password'] = 'required|min:1' ;

        $this->validationData();

        return $this->_registerRules;
    }

    /**
     * @return array
     */
    public function validationData()
    {
        $validateConfirmPassword = false;
        $validateEmail = false;
        $validateUsername = false;

        $inputs = $this->query->all();
        $inputsRequest = $this->request->all();
        $inputs = array_merge($inputs, $inputsRequest);

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
            $this->_registerRules['email'] = 'required|string|max:255|unique:users';
        }

        if (isset($inputs['confirm_password'])) {
            $validateConfirmPassword = true;
        }

        if ($validateUsername) {
            $this->_registerRules['username'] = 'required|string|max:255|unique:users';
        }

        if ($validateConfirmPassword) {
            $this->_registerRules['confirm_password'] = 'required|min:1|same:password';
        }

        $captcha_disabled = get_option('captcha_disabled', 'users') == 'y';
        if (!$captcha_disabled) {
            $this->_registerRules['captcha'] = 'required|min:1|captcha';
        }

        // 'terms' => 'terms:terms_user,terms_newsletter',
        /// 'email' => 'string|max:255|unique:users',

        return parent::validationData();
    }


}
