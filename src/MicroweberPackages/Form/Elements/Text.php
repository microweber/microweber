<?php

namespace MicroweberPackages\Form\Elements;

class Text extends Input
{
    protected $attributes = [
        'type' => 'text',
        'class'=>'form-control'
    ];

    public function render()
    {
        return sprintf('<input%s>', $this->renderAttributes());
    }


    public function placeholder($placeholder)
    {
        $this->setAttribute('placeholder', $placeholder);

        return $this;
    }

    public function defaultValue($value)
    {
        if (! $this->hasValue()) {
            $this->setValue($value);
        }

        return $this;
    }

    protected function hasValue()
    {
        return isset($this->attributes['value']);
    }
}
