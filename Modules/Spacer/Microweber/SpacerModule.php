<?php

namespace Modules\Spacer\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Spacer\Filament\SpacerModuleSettings;

class SpacerModule extends BaseModule
{
    public static string $name = 'Spacer';
    public static string $module = 'spacer';
    public static string $icon = 'modules.spacer-icon';
    public static string $categories = 'essentials';
    public static string $templatesNamespace = 'modules.spacer::templates';
    public static string $settingsComponent = SpacerModuleSettings::class;
    public static int $position = 13;

    public function getViewData(): array
    {
        $height = $this->getOption('height');

        if (!$height) {
            $height = $this->params['height'] ?? '';
        }

        $styles = [];
        if ($height) {
            $styles[] = 'height: ' . $height;
        }

        $stylesAttr = '';
        if (!empty($styles)) {
            $stylesAttr = implode(';', $styles);
        }

        return [
            'height' => $height,
            'styles_attr' => $stylesAttr,
            'module_id' => $this->params['id'] ?? uniqid('spacer-')
        ];
    }

    public function render()
    {
        $viewData = $this->getViewData();
        $viewName = $this->getViewName($viewData['template'] ?? 'default');

        return view($viewName, $viewData);
    }
}
