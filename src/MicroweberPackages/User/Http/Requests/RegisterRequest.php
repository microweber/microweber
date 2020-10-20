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
        $this->_registerRules['confirm_password'] = 'required|min:1|same:password' ;

        return $this->_registerRules;
    }

    /**
     * @return array
     */
    public function validationData()
    {
        $validateEmail = false;
        $validateUsername = false;

        $inputs = $this->query->all();

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

        if ($validateUsername) {
            $this->_registerRules['username'] = 'required|string|max:255|unique:users';
        }

        // 'terms' => 'terms:terms_user,terms_newsletter',
        /// 'email' => 'string|max:255|unique:users',

        return parent::validationData();
    }
}
