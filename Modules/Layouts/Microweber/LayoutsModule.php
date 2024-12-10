<?php

namespace Modules\Layouts\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Layouts\Filament\LayoutsModuleSettings;

class LayoutsModule extends BaseModule
{
    public static string $name = 'Layouts';
    public static string $module = 'layouts';
    public static string $xicon = "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+CiAgICA8Zz4KICAgICAgICA8cGF0aCBmaWxsPSJub25lIiBkPSJNMCAwaDI0djI0SDB6Ii8+CiAgICAgICAgPHBhdGggZD0iTTUgOGgxNFY1SDV2M3ptOSAxMXYtOUg1djloOXptMiAwaDN2LTloLTN2OXpNNCAzaDE2YTEgMSAwIDAgMSAxIDF2MTZhMSAxIDAgMCAxLTEgMUg0YTEgMSAwIDAgMS0xLTFWNGExIDEgMCAwIDEgMS0xeiIvPgogICAgPC9nPgo8L3N2Zz4K";
    public static string $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
        <g>
            <path fill="none" d="M0 0h24v24H0z"/>
            <path d="M5 8h14V5H5v3zm9 11v-9H5v9h9zm2 0h3v-9h-3v9zM4 3h16a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1z"/>
        </g>
    </svg>';

    public static string $categories = 'content';
    public static int $position = 1;
    public static string $settingsComponent = LayoutsModuleSettings::class;
    public static string $templatesNamespace = 'modules.layouts::templates';

    public function render()
    {
        $viewData = $this->getViewData();
        $classes = mw_get_layout_css_classes($viewData['params']);
        $viewData['classes'] = $classes;
        $viewName = $this->getViewName($viewData['template'] ?? 'default');

        return view($viewName, $viewData);
    }


}
