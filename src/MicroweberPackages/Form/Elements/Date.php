<?php

namespace MicroweberPackages\Form\Elements;

class Date extends Text
{
    protected $attributes = [
        'type' => 'date',
    ];

    public function value($value)
    {
        if ($value instanceof \DateTime) {
            $value = $value->format('Y-m-d');
        }

        return parent::value($value);
    }

    public function defaultValue($value)
    {
        if (! $this->hasValue()) {
            if ($value instanceof \DateTime) {
                $value = $value->format('Y-m-d');
            }
            $this->setValue($value);
        }

        return $this;
    }
}
