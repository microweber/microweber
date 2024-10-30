<?php

namespace Modules\FacebookLike\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\FacebookLike\Filament\FacebookLikeModuleSettings;

class FacebookLikeModule extends BaseModule
{
    public static string $name = 'Facebook Like';
    public static string $module = 'facebook_like';
    public static string $icon = 'heroicon-o-thumb-up';
    public static string $categories = 'social';
    public static int $position = 1;
    public static string $settingsComponent = FacebookLikeModuleSettings::class;

    public static string $templatesNamespace = 'modules.facebook_like::templates';

    public function getViewData(): array
    {
        $viewData = parent::getViewData();

        $viewData['layout'] = $this->getOption('layout', 'standard');
        $viewData['url_to_like'] = $this->getOption('url', url_current(true));
        $viewData['color'] = $this->getOption('color', 'light');
        $viewData['show_faces'] = $this->getOption('show_faces', true) ? 'true' : 'false';

        return $viewData;
    }

    public function render()
    {
        $viewData = $this->getViewData();
        $template = isset($viewData['template']) ? $viewData['template'] : 'default';

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
