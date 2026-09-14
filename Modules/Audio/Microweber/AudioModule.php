<?php

namespace Modules\Audio\Microweber;

use MicroweberPackages\ModuleRegistry\Abstract\BaseModule;
use Modules\Audio\Filament\AudioModuleSettings;
use Illuminate\View\View;

class AudioModule extends BaseModule
{
    // Module configuration
    public static string $name = 'Audio';
    public static string $module = 'audio';
    public static string $icon = 'modules.audio-icon';
    public static string $categories = 'media, music';
    public static int $position = 20;
    public static string $settingsComponent = AudioModuleSettings::class;
    public static string $templatesNamespace = 'modules.audio::templates';

    private const ID_PREFIX = 'mwaudio-';

    public function render(): View
    {
        $viewData = $this->getViewData();
        $viewData = array_merge($viewData, $this->getAudioData());

        return view(static::$templatesNamespace . '.default', $viewData);
    }

    public function getAudioData(): array
    {
        $id = self::ID_PREFIX . $this->params['id'];
        $audio = $this->params['data-audio-url'] ?? false;

        if (!$audio) {
            $audioSource = get_module_option('data-audio-source', $this->params['id']);
            $audioUpload = get_module_option('data-audio-upload', $this->params['id']);
            $audioUrl = get_module_option('data-audio-url', $this->params['id']);

            $audio = $audioSource === 'url' ? $audioUrl : $audioUpload;
        }

        if (empty($audio)) {
            $audio = ' ';
        }

        // task-2026-09-14-qskit — playback options exposed by the quick-settings
        // panel + the full settings form. "Show controls" defaults ON (an audio
        // player with no controls is useless unless deliberately hidden).
        $autoplay = filter_var(get_module_option('autoplay', $this->params['id']), FILTER_VALIDATE_BOOLEAN);
        $loop = filter_var(get_module_option('loop', $this->params['id']), FILTER_VALIDATE_BOOLEAN);
        $controlsOpt = get_module_option('show_controls', $this->params['id']);
        $controls = ($controlsOpt === null || $controlsOpt === '' || $controlsOpt === false)
            ? true : filter_var($controlsOpt, FILTER_VALIDATE_BOOLEAN);

        return [
            'audio' => $audio,
            'id' => $id,
            'autoplay' => $autoplay,
            'loop' => $loop,
            'controls' => $controls,
        ];
    }
}
