<?php

namespace Modules\Logo\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Logo\Filament\LogoModuleSettings;

class LogoModule extends BaseModule
{
    public static string $name = 'Logo';
    public static string $module = 'logo';
    public static string $icon = 'modules.logo-icon';
    public static string $categories = 'miscellaneous';
    public static int $position = 39;
    public static string $settingsComponent = LogoModuleSettings::class;

    public static string $templatesNamespace = 'modules.logo::templates';

    public function render()
    {
        $viewData = $this->getViewData();
        $logoOptions = $this->getOptions();

        $viewData['logoimage'] = $logoOptions['logoimage'] ?? '';
        $viewData['text'] = $logoOptions['text'] ?? '';
        $viewData['text_color'] = $logoOptions['text_color'] ?? '#000';
        $viewData['font_family'] = $logoOptions['font_family'] ?? 'inherit';
        $viewData['font_size'] = $logoOptions['font_size'] ?? '30';
        $viewData['size'] = $logoOptions['size'] ?? '200'; // Default size

        $template = isset($viewData['template']) ? $viewData['template'] : 'default';

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
