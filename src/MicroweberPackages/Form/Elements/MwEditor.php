<?php

namespace MicroweberPackages\Form\Elements;

class MwEditor extends FormControl
{
    protected $attributes = [
        'class'=>'form-control',
        'name' => '',
        'rows' => 10,
        'cols' => 50,
    ];

    protected $value;

    public function render()
    {
        return implode([
            sprintf('<textarea%s>', $this->renderAttributes()),
            $this->escape($this->value),
            '</textarea>',
        ]);
    }

    public function rows($rows)
    {
        $this->setAttribute('rows', $rows);

        return $this;
    }

    public function cols($cols)
    {
        $this->setAttribute('cols', $cols);

        return $this;
    }

    public function value($value)
    {
        $this->value = $value;

        return $this;
    }

    public function placeholder($placeholder)
    {
        $this->setAttribute('placeholder', $placeholder);

        return $this;
    }

    public function defaultValue($value)
    {
        if (! $this->hasValue()) {
            $this->value($value);
        }

        return $this;
    }

    protected function hasValue()
    {
        return isset($this->value);
    }
}
