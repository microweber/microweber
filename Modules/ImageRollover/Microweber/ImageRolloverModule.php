<?php

namespace Modules\ImageRollover\Microweber;


use Modules\ImageRollover\Filament\ImageRolloverModuleSettings;
use MicroweberPackages\Microweber\Abstract\BaseModule;

class ImageRolloverModule extends BaseModule
{

    public static string $name = 'Image Rollover';
    public static string $module = 'image_rollover';
    public static string $icon = 'modules.image_rollover-icon';
    public static string $categories = 'media';
    public static int $position = 3;
    public static string $settingsComponent = ImageRolloverModuleSettings::class;

    public static string $templatesNamespace = 'modules.image_rollover::templates';


    public function render()
    {
        $viewData = $this->getViewData();

        $default_image = get_option('default_image', $this->params['id'] ?? $this->params['default_image'] ?? '');
        $rollover_image = get_option('rollover_image', $this->params['id'] ?? $this->params['rollover_image'] ?? '');
        $text = get_option('text', $this->params['id'] ?? $this->params['text'] ?? '');
        $href_url = get_option('href-url', $this->params['id'] ?? $this->params['href_url'] ?? '');
        $size = get_option('size', $this->params['id'] ?? $this->params['size'] ?? '');


        if (!$default_image) {
            $default_image = asset('modules/image_rollover/img/default_image.jpg');
        }

        if ($text == false || $text == '') {
            if (isset($this->params['text'])) {
                $text = $this->params['text'];
            }
        }

        if ($size == false || $size == '') {
            if (isset($this->params['size'])) {
                $size = $this->params['size'];
            } else {
                $size = 60;
            }
        }

        $template = isset($viewData['template']) ? $viewData['template'] : 'default';

        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }
        $viewData['default_image'] = $default_image ?? '';
        $viewData['rollover_image'] = $rollover_image ?? '';
        $viewData['text'] = $text ?? '';
        $viewData['href_url'] = $href_url ?? '';
        $viewData['size'] = $size ?? '';


        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
