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

    public function render()
    {
        $viewData = $this->getViewData();

        if (!isset($params['url'])) {
            $params['url'] = 'https://youtu.be/3PZ65s2qLTE';
            $params['data-url'] = 'https://youtu.be/3PZ65s2qLTE';
        }

        $renderData = render_video_module($this->params);
        $viewData = array_merge($viewData, $renderData);

        $template = $viewData['template'] ?? 'default';
        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);

    }

}
