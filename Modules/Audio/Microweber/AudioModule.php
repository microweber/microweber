<?php

namespace Modules\Audio\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Audio\Filament\AudioModuleSettings;
use Illuminate\View\View;

class AudioModule extends BaseModule
{
    // Module configuration
    public static string $name = 'Audio';
    public static string $module = 'audio';
    public static string $icon = 'modules.audio-icon';
    public static string $categories = 'media, music';
    public static int $position = 2;
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

        return [
            'audio' => $audio,
            'id' => $id
        ];
    }
}
