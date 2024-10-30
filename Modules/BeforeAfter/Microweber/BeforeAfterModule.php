<?php

namespace Modules\BeforeAfter\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\BeforeAfter\Filament\BeforeAfterModuleSettings;

class BeforeAfterModule extends BaseModule
{
    public static string $name = 'BeforeAfter';
    public static string $module = 'beforeafter';
    public static string $icon = 'modules.beforeafter-icon';
    public static string $categories = 'media';
    public static int $position = 3;
    public static string $settingsComponent = BeforeAfterModuleSettings::class;

    public static string $templatesNamespace = 'modules.beforeafter::templates';

    public function getViewData(): array
    {
        $viewData = parent::getViewData();

        $viewData['before'] = $this->getOption('before', asset('modules/beforeafter/img/white-car.jpg'));
        $viewData['after'] = $this->getOption('after',  asset('modules/beforeafter/img/blue-car.jpg'));
        $viewData['id'] = $this->params['id'];

        return $viewData;
    }

    public function render()
    {
        $viewData = $this->getViewData();
        $template = isset($viewData['template']) ? $viewData['template'] : 'default';

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
