<?php

namespace MicroweberPackages\Form\Elements;

class DateTimeLocal extends Text
{
    protected $attributes = [
        'type' => 'datetime-local',
    ];

    public function value($value)
    {
        if ($value instanceof \DateTime) {
            $value = $value->format('Y-m-d\TH:i');
        }

        return parent::value($value);
    }

    public function defaultValue($value)
    {
        if (! $this->hasValue()) {
            if ($value instanceof \DateTime) {
                $value = $value->format('Y-m-d\TH:i');
            }
            $this->setValue($value);
        }

        return $this;
    }
}
