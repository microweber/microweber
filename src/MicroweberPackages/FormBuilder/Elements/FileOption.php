<?php

namespace MicroweberPackages\FormBuilder\Elements;

class FileOption extends Text
{
    protected $attributes = [
        'type' => 'text',
        'class'=>'form-control mw_option_field'
    ];

    protected $model;
    protected $optionKey;
    protected $optionGroup;

    public function __construct($optionKey, $optionGroup) {

        $this->optionKey = $optionKey;
        $this->optionGroup = $optionGroup;

        $this->setName($optionKey);
        $this->setAttribute('option-group', $optionGroup);

        $this->model = \MicroweberPackages\Option\Models\ModuleOption::where('option_key', $this->optionKey)->where('option_group', $this->optionGroup)->first();
        if ($this->model) {
            $this->value($this->model->option_value);
        }
    }


    public function getType()
    {
        return 'text-option';
    }
}
