<?php

namespace Modules\Slider\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Slider\Filament\SliderModuleSettings;

class SliderModule extends BaseModule
{
    public static string $name = 'Slider';
    public static string $module = 'slider';
    public static string $icon = 'modules.slider-icon';
    public static string $categories = 'media';
    public static int $position = 10;
    public static string $settingsComponent = SliderModuleSettings::class;
    public static string $templatesNamespace = 'modules.slider::templates';

    public function render()
    {
        $viewData = $this->getViewData();
        $slides = @json_decode($this->getOption('slides'), true) ?? [];

        if (!$slides) {
            $slides = $this->getDefaultSlides();
        }
        $viewData = array_merge($viewData, ['slides' => $slides]);

        $template = $viewData['template'] ?? 'default';
        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }

    public function getDefaultSlides()
    {
        return [
            [

                'title' => 'Default Slide 1',
                'description' => 'This is a default slide description.',
                'image' => asset('modules/slider/img/default-slide-1.jpg'),
                'buttonText' => 'Learn More',
                'url' => '#',
                'alignItems' => 'center',
            ],
        ];
    }
}
