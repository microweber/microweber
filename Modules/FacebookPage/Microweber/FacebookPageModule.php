<?php

namespace Modules\FacebookPage\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\FacebookPage\Filament\FacebookPageModuleSettings;

class FacebookPageModule extends BaseModule
{
    public static string $name = 'Facebook Page';
    public static string $module = 'facebook_page';
    public static string $icon = 'modules.facebook_page-icon';
    public static string $categories = 'social';
    public static int $position = 1;
    public static string $settingsComponent = FacebookPageModuleSettings::class;
    public static string $templatesNamespace = 'modules.facebook_page::templates';

    public function getViewData(): array
    {
        $viewData = parent::getViewData();

        $viewData['fbPage'] = $this->getOption('fbPage', 'https://www.facebook.com/Microweber/');
        $viewData['width'] = $this->getOption('width', '380');
        $viewData['height'] = $this->getOption('height', '300');
        $viewData['friends'] = $this->getOption('friends', false) ? 'true' : 'false';
        $viewData['timeline'] = $this->getOption('timeline', false) ? '&tabs=timeline' : '';

        return $viewData;
    }

    public function render()
    {
        $viewData = $this->getViewData();
        $template = isset($viewData['template']) ? $viewData['template'] : 'default';

        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
