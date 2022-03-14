<?php
namespace MicroweberPackages\Post\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
        // todo with multilanguage

        $rules = [
            'title' => 'required|max:500',
            'url' => 'max:500',
            'description' => 'max:500',
            'content_meta_title' => 'max:500',
            'content_meta_keywords' => 'max:500',
            'original_link' => 'max:500',
        ];

        return $rules;
    }
}
