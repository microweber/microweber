<?php

namespace Modules\Elements\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;

class InlineTableElementModule extends BaseModule
{
    public static string $name = 'Inline Table';
    public static string $module = 'inline-table';
    public static string $icon = 'modules.elements-table';
    public static string $categories = 'essentials';
    public static int $position = 5;
    public static string $templatesNamespace = 'modules.elements::templates.inline-table';
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
