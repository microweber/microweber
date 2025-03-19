<?php

namespace Modules\Background\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Background\Filament\BackgroundModuleSettings;

class BackgroundModule extends BaseModule
{
    public static string $name = 'Background';
    public static string $module = 'background';
    public static string $icon = 'modules.background-icon';
    public static string $categories = 'media';
    public static string $templatesNamespace = 'modules.background::templates';
    public static string $settingsComponent = BackgroundModuleSettings::class;
    public static int $position = 1;

    public function getViewData(): array
    {
        $background_image_option = $this->getOption('data-background-image' );
        $background_size_option = $this->getOption('data-background-size' );
        $background_color_option = $this->getOption('data-background-color' );
        $background_video_option = $this->getOption('data-background-video' );
        if($background_video_option == 'none'){
            $background_video_option = '';
        }


        $background_image = $background_image_option ?? $this->params['data-background-image'] ?? $this->params['background-image'] ?? '';
        $background_size = $background_size_option ?? $this->params['data-background-size'] ?? $this->params['background-size'] ?? '';
        $background_color = $background_color_option ?? $this->params['data-background-color'] ?? $this->params['background-color'] ?? '';
        $background_video = $background_video_option ?? $this->params['data-background-video'] ?? $this->params['background-video'] ?? '';
        $style_attributes_overlay = [];
        $style_attributes = [];

        if ($background_image == 'none') {
            $background_image = '';
        }

        $background_image_attr_style = '';
        if ($background_image) {
            $style_attributes[] = 'background-image: url(' . $background_image . ')';
        }


        if ($background_size) {
            $style_attributes[] = 'background-size: ' . $background_size;
        }

        $style_attr = '';
        if ($background_color_option) {
            $background_color = $background_color_option;
        }

        if ($background_color != '') {
            $style_attributes_overlay[] = 'background-color: ' . $background_color;
        }
        $video_url = $background_video;
        if ($video_url == 'none') {
            $video_url = '';
        }

        $video_html = '';
        $video_attr_parent = '';
        if ($video_url) {
            $video_html = '<video src="' . $video_url . '" autoplay muted loop playsinline></video>';
            $video_attr_parent = ' data-mwvideo="' . $video_url . '" ';
        }
        if ($style_attributes) {
            $style_attr_items = implode('; ', $style_attributes);
            $style_attr = 'style="' . $style_attr_items . '"';
        }

        $style_attr_overlay = '';
        if ($background_color != '' || $background_image != '') {
            //   $style_attr_overlay = 'style="background-color: rgba(0,0,0,0.5);"';
        }

        if ($style_attributes_overlay) {
            $style_attributes_overlay_items = implode('; ', $style_attributes_overlay);
            $style_attr_overlay = 'style="' . $style_attributes_overlay_items . '"';
        }


        return [
            'background_image' => $background_image,
            'background_video' => $video_url,
            'background_color' => $background_color ?? $background_color_option ?? $this->params['data-background-color'] ?? '',
            'style_attr' => $style_attr,
            'style_attr_overlay' => $style_attr_overlay,
            'video_html' => $video_html,
            'video_attr_parent' => $video_attr_parent,
        ];
    }

    public function render()
    {
        $viewData = $this->getViewData();
        $viewName = $this->getViewName($viewData['template'] ?? 'default');

        return view($viewName, $viewData);
    }
}
