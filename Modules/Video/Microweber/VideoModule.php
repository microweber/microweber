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

    /**
     * Sample video URLs for demo/placeholder content organized by category
     */
    private array $demoVideoUrls = [
        'Nature' => [
            'https://youtu.be/3PZ65s2qLTE' => 'Wildlife Documentary',
            'https://www.youtube.com/watch?v=UV0mhY2Dxr0' => 'Ocean Life',
            'https://www.youtube.com/watch?v=H4tyzzP33Cw' => 'Science Relax'
        ]
    ];

    /**
     * Render the video module
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        $this->handleEmbedUrl();

        $viewData = $this->prepareViewData();
        $template = $this->resolveTemplate($viewData);

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }

    /**
     * Handle embed URL initialization
     */
    private function handleEmbedUrl(): void
    {
        $embedUrl = get_option('embed_url', $this->params['id']);
        if (empty($embedUrl)) {
            // Select random category
            $randomCategory = array_rand($this->demoVideoUrls);
            // Select random video from category
            $randomVideo = array_rand($this->demoVideoUrls[$randomCategory]);
            save_option('embed_url', $randomVideo, $this->params['id']);
        }
    }

    /**
     * Prepare view data for rendering
     *
     * @return array
     */
    private function prepareViewData(): array
    {
        $viewData = $this->getViewData();
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
