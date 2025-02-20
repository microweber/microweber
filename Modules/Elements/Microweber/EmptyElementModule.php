<?php

namespace Modules\Elements\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;

class EmptyElementModule extends BaseModule
{
    public static string $name = 'Empty';
    public static string $module = 'empty';
    public static string $icon = 'modules.elements-empty';
    public static string $categories = 'essentials';
    public static int $position = 3;
    public static string $templatesNamespace = 'modules.elements::templates.empty';
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
