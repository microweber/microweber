<?php

namespace MicroweberPackages\Form\Elements;

class TextOption extends Input
{
    protected $optionKey;
    protected $optionGroup;

    public $attributes = [
        'class'=>'form-control mw_option_field',
    ];

    public function __construct($optionKey, $optionGroup) {

        $this->optionKey = $optionKey;
        $this->optionGroup = $optionGroup;

        $this->setName($optionKey);
        $this->setAttribute('option-group', $optionGroup);
    }

    public function render()
    {
        return sprintf('<input%s>', $this->renderAttributes());
    }
}
