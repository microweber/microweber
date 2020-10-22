<?php
namespace MicroweberPackages\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidatesWhenResolvedTrait;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
{

    /**
     * @return bool
     */
    public function authorize()
    {

        $enable_user_gesitration = get_option('enable_user_registration', 'users');
        if ($enable_user_gesitration == 'n') {
            return false;
        }

        return true;
    }


    public function rules()
    {
        return[];
    }
}
