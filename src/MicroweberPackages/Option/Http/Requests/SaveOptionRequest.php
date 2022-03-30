<?php

namespace MicroweberPackages\Option\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveOptionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.s
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
              'option_key' => 'required|max:500',
              'option_group' => 'required|max:500',
        ];
        return $rules;
    }
}
