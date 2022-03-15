<?php
namespace MicroweberPackages\Product\Http\Requests;

class ProductUpdateRequest extends ProductRequest
{
    /**
     * Get the validation rules that apply to the request.s
     *
     * @return array
     */
    public function rules()
    {
        $this->rules['title'] = 'max:500';

        return parent::rules();
    }
}
