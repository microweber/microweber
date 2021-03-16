<?php


namespace MicroweberPackages\App\Http\Controllers;

class ModuleFrontController
{
    public $moduleParams = [];
    public $moduleConfig = [];

    public function setModuleParams($params)
    {
        $this->moduleParams = $params;
    }

    public function setModuleConfig($config)
    {
        $this->moduleConfig = $config;
    }

    public function view($view = false, $data = [], $return = false)
    {
        $data['params'] = $this->moduleParams;
        $data['config'] = $this->moduleConfig;

        $moduleTemplate = get_option('data-template', $this->moduleParams['id']);
        if ($moduleTemplate == false and isset($this->moduleParams['template'])) {
            $moduleTemplate = $this->moduleParams['template'];
        }

        if ($moduleTemplate != false) {
            $templateFile = module_templates($this->moduleConfig['module'], $moduleTemplate);
        } else {
            $templateFile = module_templates($this->moduleConfig['module'], 'default');
        }

        if (strpos($view, '::') !== false) {
            return view($view, $data);
        } else {
            view()->addNamespace($this->moduleConfig['module'], dirname($templateFile));

            if ($view) {
                return view($this->moduleConfig['module'] . '::' . $view, $data);
            }

            return view($this->moduleConfig['module'] . '::' . no_ext(basename($templateFile)), $data);
        }
    }
}
