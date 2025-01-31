<?php

namespace Modules\Video\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Audio\Filament\AudioModuleSettings;
use Modules\Video\Filament\VideoModuleSettings;

class VideoModule extends BaseModule
{
    public static string $name = 'Video module';
    public static string $module = 'video';
    public static string $icon = 'heroicon-o-rectangle-stack';
    public static string $categories = 'media, video';
    public static int $position = 2;
    public static string $settingsComponent = VideoModuleSettings::class;
    public static string $templatesNamespace = 'modules.video::templates';

    public $randomEmbedVideoUrls = [
        'https://youtu.be/3PZ65s2qLTE',
        'https://www.youtube.com/watch?v=UV0mhY2Dxr0',
        'https://www.youtube.com/watch?v=H4tyzzP33Cw',
        'https://www.youtube.com/watch?v=HwGgXZI1J-o'
    ];

    public function render()
    {
        $checkForEmbedUrl = $this->getOption('embed_url', $this->params['id']);
        if ($checkForEmbedUrl) {
            $randomUrl = $this->randomEmbedVideoUrls[array_rand($this->randomEmbedVideoUrls)];
            save_option('embed_url', $randomUrl, $this->params['id']);
        }

        $viewData = $this->getViewData();
        $renderData = renderVideoModule($this->params);
        $viewData = array_merge($viewData, $renderData);

        $template = $viewData['template'] ?? 'default';
        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);

    }

}
