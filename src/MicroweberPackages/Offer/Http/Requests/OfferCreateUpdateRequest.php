<?php

namespace MicroweberPackages\Offer\Http\Requests;

use Illuminate\Http\Request;

class OfferCreateUpdateRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'offer_price' => 'sometimes|integer',
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