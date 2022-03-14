<?php
namespace MicroweberPackages\Page\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
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
            'title' => 'required|max:500',
            'url' => 'max:500',
            'content_meta_title' => 'max:500',
            'content_meta_keywords' => 'max:500',
            'original_link' => 'max:500',
        ];

        return $rules;
    }
}
