<?php

namespace MicroweberPackages\Product\Validators;

class PriceValidator
{
    public function validate($attribute, $value, $parameters)
    {
        if ($value < 0) {
            return false;
        }

        $value = floatval($value);

        return $value;
    }
}
