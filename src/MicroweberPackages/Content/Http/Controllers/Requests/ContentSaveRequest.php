<?php

namespace MicroweberPackages\Content\Http\Controllers\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationRuleParser;
use MicroweberPackages\Content\Content;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;

class ContentSaveRequest extends FormRequest
{
    public $model = Content::class;
    public $rules = [
        'title' => 'required|max:500',
        'url' => 'max:500',
        'content_meta_title' => 'max:500',
        'content_meta_keywords' => 'max:500',
        'original_link' => 'max:500',
    ];

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
        return $this->rulesWithMultilanguage($this->rules);
    }

    public function rulesWithMultilanguage($rules)
    {
        if (MultilanguageHelpers::multilanguageIsEnabled()) {
            if (isset($this->model)) {
                if (isset(app()->make($this->model)->translatable)) {

                    $newRules = [];
                    $translatableFields = app()->make($this->model)->translatable;
                    $defaultLocale = default_lang();
                    $supportedLocales = get_supported_languages(true);
                    foreach ($rules as $field=>$fieldRule) {
                        if (in_array($field, $translatableFields)) {
                            if (!empty($supportedLocales)) {
                                foreach ($supportedLocales as $supportedLocale) {
                                   if ($supportedLocale['locale'] != $defaultLocale) {
                                       if (is_string($fieldRule)) {
                                           $fieldRule = str_ireplace('required|', false, $fieldRule);
                                           $fieldRule = str_ireplace('|required', false, $fieldRule);
                                       }
                                   }
                                   $newRules['multilanguage.' . $field . '.' . $supportedLocale['locale']] = $fieldRule;
                                }
                            } else {
                                $newRules['multilanguage.' . $field . '.' . $defaultLocale] = $fieldRule;
                            }
                        } else {
                            $newRules[$field] = $fieldRule;
                        }
                    }

                    $rules = $newRules;
                }
            }
        }

        return $rules;
    }
}
