<?php

namespace Modules\Category\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Category\Filament\CategoryModuleSettings;

class CategoryModule extends BaseModule
{
    public static string $name = 'Category';
    public static string $module = 'categories';
    public static string $icon = 'modules.category-icon';
    public static string $categories = 'navigation';
    public static int $position = 1;
    public static string $settingsComponent = CategoryModuleSettings::class;
    public static string $templatesNamespace = 'modules.category::templates';


    public function render()
    {
        $viewData = $this->getViewData();
        $viewName = $this->getViewName($viewData['template'] ?? 'default');
        return view($viewName, $viewData);
    }
}
