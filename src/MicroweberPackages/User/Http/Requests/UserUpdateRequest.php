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
        $ignore_id =  \Illuminate\Validation\Rule::unique('users')->ignore($this->user()->id);
        $ignore_api_key =  \Illuminate\Validation\Rule::unique('users')->ignore($this->user()->api_key);
        $ignore_username =  \Illuminate\Validation\Rule::unique('users')->ignore($this->user()->username);
        $ignore_email =  \Illuminate\Validation\Rule::unique('users')->ignore($this->user()->email);

        return [
            'email' => ['required', 'email',$ignore_email],
            'username' => ['required',$ignore_username],
            'api_key' => [$ignore_api_key]
        ];



        return $rules;
    }
}
