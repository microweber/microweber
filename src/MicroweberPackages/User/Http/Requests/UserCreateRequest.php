<?php
namespace MicroweberPackages\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.s
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
             'password'=>'max:500',
             'first_name'=>'max:500',
             'last_name'=>'max:500',
             'phone'=>'max:500',
             'username' => 'required|unique:users,username|max:500',
             'email' => 'unique:users,email|max:500',
        ];

        return $rules;
    }
}
