<?php

namespace MicroweberPackages\Form\Elements;

class TextAreaOption extends TextArea
{
    protected $model;
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

        $this->model = \MicroweberPackages\Option\Models\ModuleOption::where('option_key', $this->optionKey)->where('option_group', $this->optionGroup)->first();
        if ($this->model) {
            if(isset($this->model->option_value)) {
                $this->value($this->model->option_value);
            }
        }
    }
}
