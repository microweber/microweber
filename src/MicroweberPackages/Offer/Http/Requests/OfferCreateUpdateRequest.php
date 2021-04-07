<?php

namespace MicroweberPackages\Offer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfferCreateUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'offer_price' => 'sometimes|numeric',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'offer_price.integer' => _e("offer price must be a number.", true)
        ];
    }
}
