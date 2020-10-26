<?php
namespace MicroweberPackages\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.s
     *
     * @return array
     */

    public function rules()
    {
        $rules = [];
        $inputs = $this->all();

        $validateEmail = false;
        $validateUsername = false;

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
            $rules['email'] = 'email|string|min:3|required|string|max:255';
        }

        if ($validateUsername) {
            $rules['username'] = 'alpha_dash|string|min:1|required|string|max:255';
        }


        if (get_option('captcha_disabled', 'users') !== 'y') {
            $rules['captcha'] = 'required|min:1|captcha';
        }

        $rules['password'] = 'required|min:1';

        return $rules;
    }
}
