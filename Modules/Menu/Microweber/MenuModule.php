<?php

namespace Modules\Menu\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Menu\Filament\MenuModuleSettings;

class MenuModule extends BaseModule
{
    public static string $name = 'Menu';
    public static string $module = 'menu';
    public static string $icon = 'modules.menu-icon';
    public static string $categories = 'navigation';
    public static int $position = 20;
    public static string $settingsComponent = MenuModuleSettings::class;

    public function render()
    {
        $viewData = $this->getViewData();
        $menuName = $this->getOption('menu_name', 'Default Menu');
        $viewData['menuName'] = $menuName;

        return view('modules.menu::templates.default', $viewData);
    }
}
