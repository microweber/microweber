<?php

namespace Modules\BeforeAfter\Microweber;

use MicroweberPackages\ModuleRegistry\Abstract\BaseModule;
use Modules\BeforeAfter\Filament\BeforeAfterModuleSettings;

class BeforeAfterModule extends BaseModule
{
    public static string $name = 'BeforeAfter';
    public static string $module = 'before_after';
    public static string $icon = 'modules.before_after-icon';
    public static string $categories = 'media';
    public static int $position = 13;
    public static string $settingsComponent = BeforeAfterModuleSettings::class;

    public static string $templatesNamespace = 'modules.before_after::templates';

    public function getViewData(): array
    {
        $viewData = parent::getViewData();

        $viewData['before'] = $this->getOption('before', asset('modules/before_after/img/white-car.jpg'));
        $viewData['after'] = $this->getOption('after',  asset('modules/before_after/img/blue-car.jpg'));
        $viewData['id'] = $this->params['id'];

        // task-2026-09-14-qskit — comparison-slider options (also on the
        // Live-Edit quick-settings panel). Passed straight into twentytwenty:
        // orientation = direction, default_offset_pct = starting split.
        $direction = $this->getOption('direction', 'horizontal');
        $viewData['direction'] = in_array($direction, ['horizontal', 'vertical'], true) ? $direction : 'horizontal';
        $startsAt = (int) $this->getOption('starts_at', 50);
        $viewData['startsAt'] = ($startsAt > 0 && $startsAt < 100) ? $startsAt : 50;

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
