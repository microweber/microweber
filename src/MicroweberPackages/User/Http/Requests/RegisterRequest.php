<?php
namespace MicroweberPackages\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.s
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'terms' => 'terms:terms_user,terms_newsletter',
            'name' => 'max:255',
            'email' => 'string|max:255|unique:users',
            'username' => 'string',
            'password' => 'required|min:1',
           // 'confirm_password' => 'required|min:1|same:password',
        ];

        return $rules;
    }
}
