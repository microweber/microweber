<?php

namespace Modules\Elements\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;

class IconElementModule extends BaseModule
{
    public static string $name = 'Icon';
    public static string $module = 'icon';
    public static string $icon = 'modules.elements-icon';
    public static string $categories = 'essentials';
    public static int $position = 6;
    public static string $templatesNamespace = 'modules.elements::templates.icon';
    public static bool $isStaticElement = true;

    public function render()
    {
        $viewData = $this->getViewData();

        $template = $viewData['template'] ?? 'default';
        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
