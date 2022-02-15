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
            'price' => 'nullable|price'
        ];

        return $rules;
    }
}
