<?php

namespace Modules\Marquee\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Marquee\Filament\MarqueeModuleSettings;

class MarqueeModule extends BaseModule
{
    public static string $name = 'Marquee';
    public static string $module = 'marquee';
    public static string $icon = 'modules.marquee-icon';
    public static string $categories = 'miscellaneous';
    public static int $position = 39;
    public static string $settingsComponent = MarqueeModuleSettings::class;

    public static string $templatesNamespace = 'modules.marquee::templates';

    public function getViewData(): array
    {
        $viewData = parent::getViewData();

        $viewData['text'] = $this->getOption('text', 'Your cool text here!');
        $viewData['fontSize'] = $this->getOption('fontSize', 46);
        $viewData['animationSpeed'] = $this->getOption('animationSpeed', 100);
        $viewData['textWeight'] = $this->getOption('textWeight', 'normal');
        $viewData['textStyle'] = $this->getOption('textStyle', 'normal');
        $viewData['textColor'] = $this->getOption('textColor', 'inherit');
        $viewData['id'] = $this->params['id'];

        if(!is_numeric( $viewData['animationSpeed'])){
            $viewData['animationSpeed'] = 100;
        }

        return $viewData;
    }

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
