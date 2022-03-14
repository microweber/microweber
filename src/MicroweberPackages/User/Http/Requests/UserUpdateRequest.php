<?php

namespace MicroweberPackages\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.s
     *
     * @return array
     */
    public function rules()
    {

        $ignore = Rule::unique('users')->ignore($this->id ?? 0, 'id');

        return [
            'first_name'=>'max:500',
            'last_name'=>'max:500',
            'phone'=>'max:500',
            'email' => [
                $ignore,
                'max:500'
            ],
            'username' => [
                $ignore,
                'max:500'
            ],
        ];

    }
}
