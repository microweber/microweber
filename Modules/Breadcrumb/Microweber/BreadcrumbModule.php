<?php

namespace Modules\Breadcrumb\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Breadcrumb\Filament\BreadcrumbModuleSettings;

class BreadcrumbModule extends BaseModule
{
    public static string $name = 'Breadcrumb';
    public static string $module = 'breadcrumb';
    public static string $icon = 'modules.breadcrumb-icon';
    public static string $categories = 'miscellaneous';
    public static int $position = 54;
    public static string $settingsComponent = BreadcrumbModuleSettings::class;
    public static string $templatesNamespace = 'modules.breadcrumb::templates';

    public function render()
    {
        $viewData = $this->getViewData();

        $breacrumb_params = [];

        if (isset($this->params['current-page-as-root'])) {
            $breacrumb_params['current-page-as-root'] = $this->params['current-page-as-root'];
        }

        $selected_start_depth = get_option('data-start-from', $this->params['id']);
        if ($selected_start_depth) {
            $breacrumb_params['start_from'] = $selected_start_depth;
        }

        $data = breadcrumb($breacrumb_params);

        $homepage = [
            'url' => site_url(),
            'title' => _e('Home', true)
        ];

        $homepage_get = app()->content_manager->homepage();
        if ($homepage_get) {
            $homepage = [
                'url' => content_link($homepage_get['id']),
                'title' => $homepage_get['title']
            ];
        }

        $template = isset($viewData['template']) ? $viewData['template'] : 'default';

        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        $viewData['data'] = $data;
        $viewData['homepage'] = $homepage;

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
