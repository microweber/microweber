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
            'email' => [
                'required',
                $ignore,
            ],
            'username' => [
                'required',
                $ignore,
            ],

        ];

    }
}
