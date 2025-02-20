<?php

namespace Modules\Elements\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;

class PictureElementModule extends BaseModule
{
    public static string $name = 'Picture';
    public static string $module = 'picture';
    public static string $icon = 'modules.elements-picture';
    public static string $categories = 'essentials';
    public static int $position = 7;
    public static string $templatesNamespace = 'modules.elements::templates.picture';
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
