<?php

namespace Modules\Audio\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Audio\Filament\AudioModuleSettings;

class AudioModule extends BaseModule
{
    public static string $name = 'Audio';
    public static string $module = 'audio';
    public static string $icon = 'modules.audio-icon';
    public static string $categories = 'media, music';
    public static int $position = 2;
    public static string $settingsComponent = AudioModuleSettings::class;
    public static string $templatesNamespace = 'modules.audio::templates';

    public function render()
    {
        $viewData = $this->getViewData();

        $id = "mwaudio-".$this->params['id'];

        $audio = false;
        if (isset($this->params['data-audio-url'])) {
            $audio = $this->params['data-audio-url'];
        }
        $audioSource = get_module_option('data-audio-source', $this->params['id']);
        $audioUpload = get_module_option('data-audio-upload', $this->params['id']);
        $audioUrl = get_module_option('data-audio-url', $this->params['id']);
        if ($audioSource == 'url') {
            $audio = $audioUrl;
        } else {
            $audio = $audioUpload;
        }

        $viewData['audio'] = $audio;
        $viewData['id'] = $id;

        return view(static::$templatesNamespace . '.default', $viewData);

    }
}
