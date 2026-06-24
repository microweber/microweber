<?php

namespace MicroweberPackages\Database\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class StrToLowerTrimCast implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return strtolower(trim($value));
    }

    public function set($model, $key, $value, $attributes)
    {
        return [$key => strtolower(trim($value))];
    }
}
