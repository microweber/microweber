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
    public static string $templatesNamespace = 'modules.menu::templates';
    public function render()
    {
        $viewData = $this->getViewData();
        $menuName = $this->getOption('menu_name')
            ?? $viewData['params']['data-name']
            ?? $viewData['params']['menu_name']
            ?? $viewData['params']['name']
            ?? 'header_menu';


        $menu_filter = [];


        $menu = get_menus('make_on_not_found=1&one=1&limit=1&title=' . $menuName);

        if (is_array($menu)) {

            if (!isset($params['ul_class'])) {
                $menu_filter['ul_class'] = 'nav';
            }
            $menu_filter['menu_id'] = intval($menu['id']);

        }
        $viewData['menu_name'] = $menuName;
        $viewData['menu_filter'] = $menu_filter;

        $viewName = $this->getViewName($viewData['template'] ?? 'default');

        return view($viewName, $viewData);
    }
}
