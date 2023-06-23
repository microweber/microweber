<?php

namespace MicroweberPackages\FormBuilder\Elements;

abstract class Input extends FormControl
{
    protected $prepend = false;
    protected $append = false;

    public function getType()
    {
        return 'input';
    }
    public function render()
    {
        $html = '';
        if ($this->prepend) {
            $html .= $this->prepend;
        }

        $html .= sprintf('<input%s>', $this->renderAttributes());

        if ($this->append) {
            $html .= $this->append;
        }
        return $html;
    }

    public function append($html){
        $this->append = $html;
        return $this;
    }

    public function prepend($html){
        $this->prepend = $html;
        return $this;
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

    public function hasValue()
    {
        return isset($this->attributes['value']);
    }
}
