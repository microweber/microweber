<?php

namespace MicroweberPackages\Product\Validators;

class PriceValidator
{
    public function validate($attribute, $value, $parameters)
    {
        if (is_null($value)) {
            return true;
        }

        $value = floatval($value);

        if ($value == 0) {
            return true;
        }

        if ($value < 0) {
            return false;
        }

        return $value;
    }
}
