<?php

namespace MicroweberPackages\Microweber\Traits;

trait HasMicroweberModuleTemplates
{

    public array $templates = [];

    public function getTemplates()
    {
        return $this->templates;
    }

    public function setTemplates(array $templates = [])
    {
        $this->templates = $templates;
    }


}
