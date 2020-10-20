<?php
namespace MicroweberPackages\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    private $_registerRules = [];

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

    public function validationData()
    {
        $inputs = $this->query->all();

        if (!isset($inputs['username']) || !isset($inputs['email'])) {
            $inputs['username'] = '';
        }

        if (isset($inputs['email'])) {
            $this->_registerRules['email'] = 'required|string|max:255|unique:users';
        }

        if (isset($inputs['username'])) {
            $this->_registerRules['username'] = 'required|string|max:255|unique:users';
        }

        // 'terms' => 'terms:terms_user,terms_newsletter',
        /// 'email' => 'string|max:255|unique:users',

        return parent::validationData();
    }
}
