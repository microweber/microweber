<?php

namespace MicroweberPackages\Form\Elements;

abstract class Input extends FormControl
{
    public function render()
    {
        return sprintf('<input%s>', $this->renderAttributes());
    }

    public function value($value)
    {
        $this->setValue($value);

        return $this;
    }

    protected function setValue($value)
    {
        $this->setAttribute('value', $value);

        return $this;
    }
}
