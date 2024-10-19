<?php

namespace MicroweberPackages\Microweber\Traits;

trait HasMicroweberModuleOptions
{

    public array $options = [];

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions(array $options = [])
    {
        $this->options = $options;
    }


}
