<?php
namespace MicroweberPackages\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    private $_registerRules = [];

    /**
     * @return bool
     */
    public function authorize()
    {

        $enable_user_gesitration = get_option('enable_user_registration', 'users');
        if ($enable_user_gesitration == 'n') {
            return false;
           // return array('error' => 'User registration is disabled.');
        }


        $no_captcha = get_option('captcha_disabled', 'users') == 'y';

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

        // 'terms' => 'terms:terms_user,terms_newsletter',
        /// 'email' => 'string|max:255|unique:users',

        return parent::validationData();
    }
}
