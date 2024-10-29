<?php

namespace MicroweberPackages\Microweber\Abstract;

use MicroweberPackages\Microweber\Liveweire\NoSettings;
use MicroweberPackages\Microweber\Traits\HasMicroweberModule;
use MicroweberPackages\Microweber\Traits\HasMicroweberModuleOptions;
use MicroweberPackages\Microweber\Traits\HasMicroweberModuleParams;
use MicroweberPackages\Microweber\Traits\HasMicroweberModuleTemplates;

abstract class BaseModule
{
    use HasMicroweberModule;
    use HasMicroweberModuleParams;
    use HasMicroweberModuleOptions;
    use HasMicroweberModuleTemplates;

    public static string $name = 'Base module';
    public static string $module = '';
    public static string $icon = '';
    public static string $categories = 'other';
    public static string $settingsComponent = NoSettings::class;
    public static int $position = 0;

    public static string $templatesNamespace = ''; //modules.my_module


    public array $params = []; // set in the constructor

    public function __construct($params = [])
    {
         $this->params = $params;


    }

    public function getViewData(): array
    {
        $options = $this->getOptions();
        $params = $this->getParams();
        $template = $this->getTemplate();
        $viewData = [
            'id' => $params['id'],
            'params' => $params,
            'template' => $template,
            'options' => $options,
        ];
        return $viewData;
    }

//    /**
//     * Render the frontend view of the module.
//     */
//    public function render()
//    {
//        return ''; // This should return the view for the frontend display
//    }
    public function render()
    {
        if (!isset(static::$templatesNamespace) || empty(static::$templatesNamespace)) {
            return '';
        }
        $viewData = $this->getViewData();
        $template = isset($viewData['template']) ? $viewData['template'] : 'default';
        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }

}
