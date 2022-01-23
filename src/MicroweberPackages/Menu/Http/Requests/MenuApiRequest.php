<?php
namespace MicroweberPackages\Menu\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuApiRequest extends FormRequest
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
     * Get the validation rules that apply to the request.s
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'title' => 'required',
        ];

        return $rules;
    }
}
