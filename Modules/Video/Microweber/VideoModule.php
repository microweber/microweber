<?php

namespace Modules\Video\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Video\Filament\VideoModuleSettings;

/**
 * Class VideoModule
 *
 * Handles video embedding and rendering functionality for the Video module
 */
class VideoModule extends BaseModule
{
    /**
     * Module configuration
     */
    public static string $name = 'Video module';
    public static string $module = 'video';
    public static string $icon = 'heroicon-o-video-camera';
    public static string $categories = 'media, video';
    public static int $position = 2;
    public static string $settingsComponent = VideoModuleSettings::class;
    public static string $templatesNamespace = 'modules.video::templates';


    private array $demoVideoUrls = [
      'https://youtu.be/3PZ65s2qLTE'
    ];

    /**
     * Render the video module
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {

        $viewData = $this->prepareViewData();
        $template = $this->resolveTemplate($viewData);

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }



    /**
     * Prepare view data for rendering
     *
     * @return array
     */
    private function prepareViewData(): array
    {
        $viewData = $this->getViewData();


        $embedUrl =$viewData['options']['embed_url'] ?? $this->params['embed_url'] ?? null;
        $upload =$viewData['options']['upload'] ?? $this->params['upload'] ?? null;

        if(empty($embedUrl) and empty($upload)){
            $this->params['url'] = $this->demoVideoUrls[array_rand($this->demoVideoUrls)];
        }


        $renderData = renderVideoModule($this->params);

        return array_merge($viewData, $renderData);
    }

    /**
     * Resolve the template to use
     *
     * @param array $viewData
     * @return string
     */
    private function resolveTemplate(array $viewData): string
    {
        $template = $viewData['template'] ?? 'default';

        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            return 'default';
        }

        return $template;
    }
}
