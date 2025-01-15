<?php

namespace Modules\Slider\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Slider\Filament\SliderModuleSettings;
use Modules\Slider\Models\Slider;

class SliderModule extends BaseModule
{
    public static string $name = 'Slider';
    public static string $module = 'slider';
    public static string $icon = 'modules.slider-icon';
    public static string $categories = 'media';
    public static int $position = 58;
    public static string $settingsComponent = SliderModuleSettings::class;
    public static string $templatesNamespace = 'modules.slider::templates';

    public function render()
    {
        $viewData = $this->getViewData();
        $rel_type = $this->params['rel_type'] ?? 'module';
        $rel_id = $this->params['rel_id'] ?? $this->params['id'];
        $viewData['slides'] = Slider::where('rel_type', $rel_type)
            ->where('rel_id', $rel_id)
            ->orderBy('position')
            ->get();

        $template = $viewData['template'] ?? 'default';
        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
