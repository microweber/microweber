<?php

namespace Modules\Elements\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;

class TextElementModule extends BaseModule
{
    public static string $name = 'Text';
    public static string $module = 'text';
    public static string $icon = 'modules.elements-text';
    public static string $categories = 'essentials';
    public static int $position = 2;
    public static string $templatesNamespace = 'modules.elements::templates.text';
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
