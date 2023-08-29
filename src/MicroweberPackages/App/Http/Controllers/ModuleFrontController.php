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
     //   $this->moduleOptions = app()->option_repository->getOptionsByGroup($this->moduleParams['id']);
    }

    public function setModuleConfig($config)
    {
        $this->moduleConfig = $config;
    }

    public function registerModule()
    {
        $moduleTemplate = get_option('template', $this->moduleParams['id']);
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
                    view()->prependNamespace($this->moduleConfig['module'], $defaultDir);
                }

                // This is the first level of template
                view()->prependNamespace($this->moduleConfig['module'], $templateDir);

                /**
                 * The right way to order tempalates
                 *
                    0 => "\userfiles\templates\theme\modules\blog\templates\filter"
                    1 => "\userfiles\templates\theme\modules\blog\templates\default"
                    2 => "\src\MicroweberPackages\Blog\resources\views\"
                 */

            } else {

            }
        }
    }

    public function view($view = false, $data = [], $return = false)
    {
        view()->getFinder()->flush();

        $this->viewData = array_merge($this->viewData, $data);

        $this->viewData['params'] = $this->moduleParams;
        $this->viewData['config'] = $this->moduleConfig;

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
