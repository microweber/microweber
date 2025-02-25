<?php

namespace Modules\Search\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Search\Filament\SearchSettings;

class SearchModule extends BaseModule
{
    public static string $name = 'Search';
    public static string $module = 'search';
    public static string $icon = 'modules.search-icon';
    public static string $categories = 'miscellaneous';
    public static int $position = 34;
    public static string $settingsComponent = SearchSettings::class;
    public static string $templatesNamespace = 'modules.search::templates';

    public function render()
    {
        $viewData = $this->getViewData();

        // Add search-specific data
        $viewData['placeholder'] = $viewData['options']['placeholder'] ?? 'Search...';
        $viewData['data_content_id'] = $viewData['options']['data-content-id'] ?? 0;
        $viewData['autocomplete'] = $viewData['options']['autocomplete'] ?? false;
        $viewData['seach_prefix'] = crc32($viewData['id']);

        // Get template from options or use default
        $template = $viewData['template'] ?? 'default';

        // If autocomplete is enabled, use the autocomplete template
        if ($viewData['autocomplete'] && view()->exists(static::$templatesNamespace . '.autocomplete')) {
            $template = 'autocomplete';
        }

        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
