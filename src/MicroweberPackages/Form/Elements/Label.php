<?php

namespace MicroweberPackages\Form\Elements;

class Label extends Element
{
    protected $element;

    protected $labelBefore;

    protected $label;

    public function __construct($label)
    {
        $this->label = $label;
    }

    public function render()
    {
        $tags = [sprintf('<label%s>', $this->renderAttributes())];

        if ($this->labelBefore) {
            $tags[] = $this->label;
        }

        $tags[] = $this->renderElement();

        if (! $this->labelBefore) {
            $tags[] = $this->label;
        }

        $tags[] = '</label>';

        return implode($tags);
    }

    public function forId($name)
    {
        $this->setAttribute('for', $name);

        return $this;
    }

    public function before(Element $element)
    {
        $this->element = $element;
        $this->labelBefore = true;

        return $this;
    }

    public function after(Element $element)
    {
        $this->element = $element;
        $this->labelBefore = false;

        return $this;
    }

    protected function renderElement()
    {
        if (! $this->element) {
            return '';
        }

        return $this->element->render();
    }

    public function getControl()
    {
        return $this->element;
    }
}
