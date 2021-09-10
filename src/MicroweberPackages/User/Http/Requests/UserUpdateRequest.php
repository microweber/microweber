<?php
namespace MicroweberPackages\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.s
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'email' => 'required|unique:users',
            'api_key' => 'unique:users',
            'username' => 'unique:users',
        ];

        return $rules;
    }
}
