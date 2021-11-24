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
             'username' => 'required|unique:users,username',
             'email' => 'unique:users,email',
        ];

        return $rules;
    }
}
