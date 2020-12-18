<?php

namespace MicroweberPackages\Database\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class StripTagsCast implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
         return strip_tags($value);
    }

    public function set($model, $key, $value, $attributes)
    {
        return [$key => strip_tags($value)];
    }
}