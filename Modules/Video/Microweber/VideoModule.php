<?php

namespace Modules\Video\Microweber;

use MicroweberPackages\ModuleRegistry\Abstract\BaseModule;
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
    public static string $name = 'Video';
    public static string $module = 'video';
    public static string $icon = 'heroicon-o-video-camera';
    public static string $categories = 'media, video';
    public static int $position = 2;
    public static string $settingsComponent = VideoModuleSettings::class;
    public static string $templatesNamespace = 'modules.video::templates';


    private array $demoVideoUrls = [
      'https://www.youtube.com/watch?v=jNXLl4Vb5xY'
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

        $embedUrl = $viewData['options']['embed_url'] ?? $this->params['embed_url'] ?? null;
        // AI-1007 / task-2026-05-22 — also check embed_code field (raw iframe HTML).
        $embedCode = $viewData['options']['embed_code'] ?? $this->params['embed_code'] ?? null;
        $upload = $viewData['options']['upload'] ?? $this->params['upload'] ?? null;

        // AI-1009 / task-2026-05-22 — track whether we are falling back to the demo video.
        // When no real source is configured, we use a demo URL but show an overlay badge
        // so editors know the video is a placeholder and not real content.
        $isDemoVideo = false;

        if (empty($embedUrl) && empty($embedCode) && empty($upload)) {
            $isDemoVideo = true;
            $this->params['url'] = $this->demoVideoUrls[array_rand($this->demoVideoUrls)];
        }

        // If a raw embed code was provided, prefer it over the URL field.
        if (!empty($embedCode)) {
            $this->params['embed_url'] = $embedCode;
        }

        $renderData = renderVideoModule($this->params);
        $renderData['isDemoVideo'] = $isDemoVideo;

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
