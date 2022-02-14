<?php
namespace MicroweberPackages\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.s
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            // 'title' => 'required', // todo with multilanguage
            'price' => 'nullable|price'
        ];

        return $rules;
    }
}
