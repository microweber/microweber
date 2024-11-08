<?php

namespace Modules\TextType\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\TextType\Filament\TextTypeModuleSettings;

class TextTypeModule extends BaseModule
{
    public static string $name = 'TextType';
    public static string $module = 'text_type';
    public static string $icon = 'modules.text_type-icon';
    public static string $categories = 'miscellaneous';
    public static int $position = 39;
    public static string $settingsComponent = TextTypeModuleSettings::class;

    public static string $templatesNamespace = 'modules.text_type::templates';

    public function getViewData(): array
    {
        $viewData = parent::getViewData();
        $viewData['text'] = $this->getOption('text', 'Your cool text here!');
        $viewData['fontSize'] = $this->getOption('fontSize', '24');
        $viewData['animationSpeed'] = $this->getOption('animationSpeed', '50');
        $viewData['id'] = $this->params['id'];
        return $viewData;
    }

    public function render()
    {
        $viewData = $this->getViewData();
        $template = isset($viewData['template']) ? $viewData['template'] : 'default';

        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
