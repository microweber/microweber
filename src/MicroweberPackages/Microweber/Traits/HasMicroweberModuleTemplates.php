<?php

namespace MicroweberPackages\Microweber\Traits;


/**
 * Trait HasMicroweberModuleTemplates
 *
 * Provides functionality to manage module templates.
 */
trait HasMicroweberModuleTemplates
{

    public array $templates = [];
    public string $template = 'default';


    /**
     * Retrieve the namespaced template path for the module.
     *
     * @return string The available templates for the module. We will use the templates from the module's namespace.
     */
    public static function getTemplatesNamespace(): string
    {

        if (!isset(static::$templatesNamespace)) {
            return '';
        }
        return static::$templatesNamespace;
    }

    /**
     * Retrieve the current template for the module.
     *
     * @return string The current template for the module.
     */
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
