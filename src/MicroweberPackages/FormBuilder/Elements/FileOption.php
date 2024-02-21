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

    }

    public function render()
    {

        return <<<HTML
            <div>
                        <module type="admin/components/file_append"
                              title="File attachments"
                              option_group="$this->optionGroup"
                              option_key="$this->optionKey" />
              </div>
  HTML;
    }

}
