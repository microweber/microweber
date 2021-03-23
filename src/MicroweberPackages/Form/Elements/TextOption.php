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
        $model = \MicroweberPackages\Option\Models\ModuleOption::where('option_key', $this->optionKey)->where('option_group', $this->optionGroup)->first();
        if ($model) {
            $this->setValue($model->option_value);
        }

        return sprintf('<input%s>', $this->renderAttributes());
    }
}
