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

        $user =$this->user();
        $ignore = false;

        if($user){
            $ignore =   Rule::unique('users')->ignore($this->user);
        }

        return [

            'email' => ['unique:users,email',$ignore],
            'username' => ['unique:users,username',$ignore],
        ];

    }
}
