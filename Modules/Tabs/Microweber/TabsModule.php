<?php

namespace Modules\Tabs\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Tabs\Filament\TabsModuleSettings;
use Modules\Tabs\Models\Tab;

class TabsModule extends BaseModule
{
    public static string $name = 'Tabs Module';
    public static string $module = 'tabs';
    public static string $icon = 'modules.tabs-icon';
    public static string $categories = 'content';
    public static int $position = 30;
    public static string $settingsComponent = TabsModuleSettings::class;
    public static string $templatesNamespace = 'modules.tabs::templates';

    public function render()
    {
        $viewData = $this->getViewData();
        $rel_type = $this->params['rel_type'] ?? 'module';
        $rel_id = $this->params['rel_id'] ?? $this->params['id'];
        $viewData['tabs'] = Tab::where('rel_type', $rel_type)->where('rel_id', $rel_id)->orderBy('position', 'asc')->get();
        $template = $viewData['template'] ?? 'default';

        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
