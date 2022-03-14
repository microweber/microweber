<?php
namespace MicroweberPackages\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.s
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'title' => 'required|max:500',
            'url' => 'max:500',
            'content_meta_title' => 'max:500',
            'content_meta_keywords' => 'max:500',
            'original_link' => 'max:500',
            'price' => 'nullable|price'
        ];

        return $rules;
    }
}
