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

    public function render()
    {
        $viewData = $this->getViewData();
        $slides = @json_decode($this->getOption('slides'), true) ?? [];

        if (!$slides) {
            $slides = $this->getDefaultSlides();
        }
        $viewData = array_merge($viewData, ['slides' => $slides]);
        return view('modules.slider::templates.default', $viewData);
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
