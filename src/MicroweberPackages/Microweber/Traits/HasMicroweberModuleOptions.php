<?php

namespace MicroweberPackages\Microweber\Traits;


trait HasMicroweberModuleOptions
{

    public array $options = [];

    public function getOptions()
    {

      //  get_module_options($this->params['id'], $this->options);
        return $this->options;
    }

    public function setOptions(array $options = [])
    {
        $this->options = $options;
    }


}
