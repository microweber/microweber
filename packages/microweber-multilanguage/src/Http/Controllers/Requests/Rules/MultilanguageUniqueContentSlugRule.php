<?php

namespace MicroweberPackages\Multilanguage\Http\Controllers\Requests\Rules;


use Illuminate\Validation\Rule;

class MultilanguageUniqueContentSlugRule extends Rule
{
    public function passes($attribute, $value)
    {
        return true;
    }

    public function message()
    {
        return 'The :attribute must be unique.';
    }
}
