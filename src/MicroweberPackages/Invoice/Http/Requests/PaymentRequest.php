<?php
namespace MicroweberPackages\Invoice\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'payment_date' => 'required',
            'customer_id' => 'required',
            'amount' => 'required',
        ];

        return $rules;
    }
}
