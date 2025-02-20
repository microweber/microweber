<?php

namespace Modules\Elements\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;

class MultipleColumnsElementModule extends BaseModule
{
    public static string $name = 'Multiple Columns';
    public static string $module = 'multiple-columns';
    public static string $icon = 'modules.elements-columns';
    public static string $categories = 'essentials';
    public static int $position = 4;
    public static string $templatesNamespace = 'modules.elements::templates.multiple-columns';
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
