<?php

namespace Modules\Video\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Audio\Filament\AudioModuleSettings;
use Modules\Video\Filament\VideoModuleSettings;

class VideoModule extends BaseModule
{
    public static string $name = 'Video module';
    public static string $icon = 'heroicon-o-rectangle-stack';
    public static string $categories = 'media, video';
    public static int $position = 2;
    public static string $settingsComponent = VideoModuleSettings::class;


    public function render()
    {
        $viewData = $this->getViewData();

        $renderData = render_video_module($this->params);
        $viewData = array_merge($viewData, $renderData);


        $moduleTemplate = get_option('template', $this->params['id']);
        if (empty($moduleTemplate)) {
            $moduleTemplate = get_option('data-template', $this->params['id']);
        }

        if ($moduleTemplate == false and isset($this->params['template'])) {
            $moduleTemplate = $this->params['template'];
        }

        $viewData['moduleTemplate'] = $moduleTemplate;

        return view('modules.video::video-module-layout', $viewData);

    }


}
