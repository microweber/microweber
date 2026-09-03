<?php

namespace Modules\Pagination\Microweber;

use MicroweberPackages\ModuleRegistry\Abstract\BaseModule;
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

        $options = $viewData['options'] ?? [];
        $show_first_last_raw = $options['show_first_last'] ?? $viewData['show_first_last'] ?? true;
        $limit_raw = $options['limit'] ?? $viewData['limit'] ?? 5;
        $show_first_last = filter_var($show_first_last_raw, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        $limit = (int) $limit_raw;

        $pagination_links = paging("num={$pages_count}&paging_param={$paging_param}&return_as_array=1&show_first_last={$show_first_last}&limit={$limit}");

        $viewData['pagination_links'] = $pagination_links;

        $template = $viewData['template'] ?? 'default';

        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
