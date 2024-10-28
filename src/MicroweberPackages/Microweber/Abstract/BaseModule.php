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
    public static string $icon = '';
    public static string $categories = 'other';
    public static string $settingsComponent = NoSettings::class;
    public static int $position = 0;

    public static string $templatesNamespace = ''; //modules.my_module


    public string $type = ''; // set in the constructor
    public array $params = []; // set in the constructor

    public function __construct($type, $params = [])
    {
        $this->type = $type;
        $this->params = $params;


    }

    public function getViewData(): array
    {
        $options = $this->getOptions();
        $params = $this->getParams();
        $template = $this->getTemplate();
        $viewData = [
            'options' => $options,
            'params' => $params,
            'template' => $template,
        ];
        return $viewData;
    }

    /**
     * Render the frontend view of the module.
     */
    public function render()
    {
        return ''; // This should return the view for the frontend display
    }


}
