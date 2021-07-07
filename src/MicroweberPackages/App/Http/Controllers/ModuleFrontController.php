<?php


namespace MicroweberPackages\App\Http\Controllers;

use MicroweberPackages\Option\Models\Option;

class ModuleFrontController
{
    public $viewData = [];
    public $viewInstance = [];
    public $moduleParams = [];
    public $moduleOptions = [];
    public $moduleConfig = [];

    public function setModuleParams($params)
    {
        $this->viewInstance = view();
        $this->moduleParams = $params;
        $this->moduleOptions = Option::where('option_group', $this->moduleParams['id'])->get();
    }

    public function setModuleConfig($config)
    {
        $this->moduleConfig = $config;
    }

    public function registerModule()
    {
        $moduleTemplate = get_option('data-template', $this->moduleParams['id']);
        // if (isset($this->moduleParams['default-template'])) {
        //       $defaultTemplate = $this->moduleParams['default-template'];
        //  }

        if ($moduleTemplate != false) {
            $templateFile = module_templates($this->moduleConfig['module'], $moduleTemplate);
        } else {
            $templateFile = module_templates($this->moduleConfig['module'], 'default');
        }

        if ($templateFile) {
            $templateDir = dirname($templateFile);
            if (is_dir($templateDir)) {

                $defaultDir = dirname($templateDir) . DS . 'default';
                if (is_dir($defaultDir)) {
                    $this->viewInstance->addNamespace($this->moduleConfig['module'], $defaultDir);
                    view()->addNamespace($this->moduleConfig['module'], $defaultDir);
                }

                $this->viewInstance->replaceNamespace($this->moduleConfig['module'], $templateDir);
                view()->replaceNamespace($this->moduleConfig['module'], $templateDir);
            }
        }
    }

    public function view($view = false, $data = [], $return = false)
    {
        $this->viewInstance->getFinder()->flush();
        view()->getFinder()->flush();

        $this->viewData = array_merge($this->viewData, $data);

        $this->viewData['params'] = $this->moduleParams;
        $this->viewData['config'] = $this->moduleConfig;

        if (strpos($view, '::') !== false) {
            return $this->viewInstance->make($view, $this->viewData);
            return view()->make($view, $this->viewData);
        } else {
            if ($view) {
                return $this->viewInstance->make($this->moduleConfig['module'] . '::' . $view, $this->viewData);
                return view()->make($this->moduleConfig['module'] . '::' . $view, $this->viewData);
            }
            // return view($this->moduleConfig['module'] . '::' . no_ext(basename($templateFile)), $this->viewData);
        }
    }
}


