<?php

namespace Modules\GoogleAnalytics\Microweber;

use Illuminate\Support\Facades\Blade;
use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\FacebookPage\Filament\FacebookPageModuleSettings;

class GoogleAnalyticsModule extends BaseModule
{
    public static string $name = 'Google Analytics';
    public static string $module = 'google_analytics';
    public static string $icon = 'modules.google_analytics-icon';
    public static string $categories = 'analytics';
    public static int $position = 10000;
     public static string $templatesNamespace = 'modules.google_analytics::templates';

    public function getViewData(): array
    {
        $viewData = parent::getViewData();

        $viewData['isEnabled'] = get_option('google-measurement-enabled', 'website') == 'y';
        $viewData['measurementId'] = get_option('google-measurement-id', 'website');


        return $viewData;
    }

    public function render()
    {
        $viewData = $this->getViewData();
        $template = isset($viewData['template']) ? $viewData['template'] : 'default';


        if(!isset($viewData['isEnabled']) || $viewData['isEnabled'] == false){
            return Blade::render('');
        }


        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
