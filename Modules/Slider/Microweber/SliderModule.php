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
        $viewData['slides'] = $this->getSlides();

        // task-2026-05-22-ce249e / AI-918 — wire SliderModuleSettings playback
        // settings to the template. Pre-fix: autoplay / autoplay_speed /
        // show_arrows / show_dots were defined in settings but never read —
        // the template hardcoded loop:true and ignored all playback controls.
        $moduleId = $this->params['id'] ?? null;
        $viewData['sliderAutoplay']      = (bool) $this->getOption('autoplay', true);
        $viewData['sliderAutoplaySpeed'] = (int)  ($this->getOption('autoplay_speed', 3000) ?: 3000);
        $viewData['sliderShowArrows']    = (bool) $this->getOption('show_arrows', true);
        $viewData['sliderShowDots']      = (bool) $this->getOption('show_dots', true);
        // AI-1014 / task-2026-05-22 — wire loop setting (default true)
        $viewData['sliderLoop']          = filter_var(
            $this->getOption('loop', true), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE
        ) ?? true;
        // AI-1015 / task-2026-05-22 — wire transition effect (default 'slide')
        $viewData['sliderEffect']        = $this->getOption('effect', 'slide') ?: 'slide';

        $template = $viewData['template'] ?? 'default';
        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }

    protected function getSlides()
    {
        $relId = $this->getRelId();
        $relType = $this->getRelType();

        $slides = Slider::where('rel_type', $relType)
            ->where('rel_id', $relId)
            ->orderBy('position')
            ->get();

        $getSlidesCreatedDefault = $this->getOption('getSlidesCreatedDefault');

        if (!$getSlidesCreatedDefault && $slides->isEmpty()) {
            $this->saveOption('getSlidesCreatedDefault', '1');
            return collect($this->getDefaultSlides());
        }

        return $slides;
    }

    protected function getDefaultSlides(): array
    {
        $defaultContent = file_get_contents(module_path(self::$module) . '/resources/default-content/default_content.json');
        $defaultContent = json_decode($defaultContent, true);

        if (!isset($defaultContent['slides'])) {
            return [];
        }

        return array_map(function ($slide) {
            $slide['media'] = app()->url_manager->replace_site_url_back($slide['media']);
            $sliderModel = new Slider();
            $slide['rel_id'] = $this->getRelId();
            $slide['rel_type'] = $this->getRelType();
            $sliderModel->fill($slide);
            $sliderModel->save();

            return $sliderModel;
        }, $defaultContent['slides']);
    }

    protected function getRelId(): ?string
    {
        return $this->getOption('rel_id')
            ?? $this->params['rel_id']
            ?? $this->params['id']
            ?? null;
    }

    protected function getRelType(): string
    {
        return $this->getOption('rel_type')
            ?? $this->params['rel_type']
            ?? 'module';
    }
}
