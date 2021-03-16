<?php


namespace MicroweberPackages\App\Http\Controllers;

use MicroweberPackages\Option\Models\Option;

class ModuleFrontController
{
    public $viewData = [];
    public $moduleParams = [];
    public $moduleOptions = [];
    public $moduleConfig = [];

    public function setModuleParams($params)
    {
        $this->moduleParams = $params;
        $this->moduleOptions = Option::where('option_group', $this->moduleParams['id'])->get();
    }

    public function setModuleConfig($config)
    {
        $this->moduleConfig = $config;
    }

    public function view($view = false, $data = [], $return = false)
    {
        if (method_exists($this, 'appendContentSchemaOrg')) {
            $this->appendContentSchemaOrg();
        }

        if (method_exists($this, 'appendContentThumbnailSize')) {
            $this->appendContentThumbnailSize();
        }

        if (method_exists($this, 'appendContentShowFields')) {
            $this->appendContentShowFields();
        }

        $this->viewData = array_merge($this->viewData, $data);

        $this->viewData['params'] = $this->moduleParams;
        $this->viewData['config'] = $this->moduleConfig;

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
            return view($view, $this->viewData);
        } else {

            view()->addNamespace($this->moduleConfig['module'], dirname($templateFile));

            if ($view) {
                return view($this->moduleConfig['module'] . '::' . $view, $this->viewData);
            }

            return view($this->moduleConfig['module'] . '::' . no_ext(basename($templateFile)), $this->viewData);
        }
    }
}
