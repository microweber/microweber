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

    public function registerModule()
    {
        $moduleTemplate = get_option('data-template', $this->moduleParams['id']);
        // if (isset($this->moduleParams['default-template'])) {
      //       $defaultTemplate = $this->moduleParams['default-template'];
       //  }
        view()->getFinder()->flush();

        if ($moduleTemplate != false) {
            $templateFile = module_templates($this->moduleConfig['module'], $moduleTemplate);
        } else {
            $templateFile = module_templates($this->moduleConfig['module'], template_name());
        }

        if ($templateFile) {
            $templateDir = dirname($templateFile);
            if (is_dir($templateDir)) {

                $defaultDir = dirname($templateDir) . DS . 'default';
                $defaultDir2 = mw_root_path().'/src/MicroweberPackages/Shop/resources/views';
                if (is_dir($defaultDir)) {
                    view()->addNamespace($this->moduleConfig['module'], $defaultDir);
                    view()->addNamespace($this->moduleConfig['module'], $defaultDir2);
                }

                if (is_dir($templateDir)) {
                    view()->replaceNamespace($this->moduleConfig['module'], $templateDir);
                    view()->addNamespace($this->moduleConfig['module'], $defaultDir);
                    view()->addNamespace($this->moduleConfig['module'], $defaultDir2);
                }

            }
        }
    }

    public function view($view = false, $data = [], $return = false)
    {
        view()->getFinder()->flush();

        $this->viewData = array_merge($this->viewData, $data);

        $this->viewData['params'] = $this->moduleParams;
        $this->viewData['config'] = $this->moduleConfig;
//dd($view);
        if (strpos($view, '::') !== false) {
            return view()->make($view, $this->viewData);
        } else {
            if ($view) {
               return view()->make($this->moduleConfig['module'] . '::' . $view, $this->viewData);
            }
            // return view($this->moduleConfig['module'] . '::' . no_ext(basename($templateFile)), $this->viewData);
        }
    }
}
