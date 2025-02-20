<?php

namespace Modules\Elements\Microweber;


use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Embed\Filament\EmbedModuleSettings;

class TitleElementModule extends BaseModule
{
    public static string $name = 'Title';
    public static string $module = 'title';
    public static string $icon = 'modules.elements-title';
    public static string $categories = 'essentials';
    public static int $position = 1;
    public static string $templatesNamespace = 'modules.elements::templates.title';
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
