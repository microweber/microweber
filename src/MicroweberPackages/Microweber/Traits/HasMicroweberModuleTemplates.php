<?php

namespace MicroweberPackages\Microweber\Traits;

trait HasMicroweberModuleTemplates
{

    public array $templates = [];
    public string $template = 'default';

    public function getTemplates()
    {
        return $this->templates;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplates(array $templates = [])
    {
        $this->templates = $templates;
    }


}
