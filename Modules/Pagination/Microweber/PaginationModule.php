<?php

namespace Modules\Pagination\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Pagination\Filament\PaginationModuleSettings;

class PaginationModule extends BaseModule
{
    public static string $name = 'Pagination';
    public static string $module = 'pagination';
    public static string $icon = 'modules.pagination-icon';
    public static string $categories = 'navigation';
    public static int $position = 100;
    public static string $settingsComponent = PaginationModuleSettings::class;
    public static string $templatesNamespace = 'modules.pagination::templates';
    protected static bool $shouldRegisterNavigation = false;

    public function render()
    {
        $viewData = $this->getViewData();

        if (!isset($viewData['paging_param']) || !isset($viewData['pages_count'])) {
            return '';
        }

        $pages_count = $viewData['pages_count'];
        $paging_param = $viewData['paging_param'];

        $pagination_links = paging("num={$pages_count}&paging_param={$paging_param}&return_as_array=1&show_first_last=1&limit=5");

        $viewData['pagination_links'] = $pagination_links;

        $template = $viewData['template'] ?? 'default';

        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
