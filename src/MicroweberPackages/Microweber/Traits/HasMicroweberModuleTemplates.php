<?php

namespace MicroweberPackages\Microweber\Traits;

trait HasMicroweberModuleTemplates
{

    public array $templates = [];
    public string $template = 'default';

    public function getTemplates()
    {
        return module_templates($this->type);
    }

    public function getTemplate()
    {
        $moduleTemplate = get_option('template', $this->params['id']);
        if (empty($moduleTemplate)) {
            $moduleTemplate = get_option('data-template', $this->params['id']);
        }
        if (empty($moduleTemplate) && isset($this->params['template'])) {
            $moduleTemplate = $this->params['template'];
        }
        return $moduleTemplate;
    }

}
