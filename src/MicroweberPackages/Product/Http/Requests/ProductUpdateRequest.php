<?php
namespace MicroweberPackages\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.s
     *
     * @return array
     */
    public function rules()
    {
        $rules = [

             //'title' => 'required',
             'price' => 'nullable|regex:/^\d+(\.\d{1,2})?$/', //The regex will hold for quantities like '12' or '12.5' or '12.05' '. If you want more decimal points than two, replace the "2" with the allowed decimals you need.
        ];

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'price.regex' => _e("Use '.' to separate the price (Ex. 9.89)", true)
        ];
    }
}
