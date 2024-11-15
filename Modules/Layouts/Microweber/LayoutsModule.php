<?php

namespace Modules\Layouts\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Layouts\Filament\LayoutsModuleSettings;

class LayoutsModule extends BaseModule
{
    public static string $name = 'Layouts';
    public static string $module = 'layouts';
    public static string $icon = 'heroicon-o-template';
    public static string $categories = 'content';
    public static int $position = 1;
    public static string $settingsComponent = LayoutsModuleSettings::class;
    public static string $templatesNamespace = 'modules.layouts::templates';

    public function render()
    {
        $viewData = $this->getViewData();
        $classes = mw_get_layout_css_classes($viewData['params']);
        $viewData['classes'] = $classes;
        $viewName = $this->getViewName($viewData['template']);

        return view($viewName, $viewData);
    }


}
