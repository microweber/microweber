<?php

namespace Modules\Category\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Category\Filament\CategoryModuleSettings;

class CategoryModule extends BaseModule
{
    public static string $name = 'Category';
    public static string $module = 'categories';
    public static string $icon = 'modules.category-icon';
    public static string $categories = 'navigation';
    public static int $position = 1;
    public static string $settingsComponent = CategoryModuleSettings::class;
    public static string $templatesNamespace = 'modules.category::templates';


    public function render()
    {
        $viewData = $this->getViewData();

        // Build category parameters
        $category_params = [];

        // Get content/page ID
        $content_id = get_option('data-content-id', $this->params['id'] ?? $this->params['content_id'] ?? $this->params['content-id'] ?? null);
        if ($content_id) {
            $category_params['content_id'] = $content_id;
        }

        // Get category ID
        $category_id = get_option('data-category-id', $this->params['id'] ?? $this->params['category_id'] ?? $this->params['category-id'] ?? null);
        if ($category_id) {
            $category_params['category_id'] = $category_id;
        }

        // Get max depth
        $max_depth = get_option('data-max-depth', $this->params['id'] ?? $this->params['max_depth'] ?? $this->params['max-depth'] ?? null);
        if ($max_depth) {
            $category_params['max_depth'] = $max_depth;
        }

        // Get other options
        $single_only = get_option('single_only', $this->params['id'] ?? $this->params['single_only'] ?? null);
        if ($single_only) {
            $category_params['single_only'] = $single_only;
        }

        $show_subcats = get_option('show_subcats', $this->params['id'] ?? $this->params['show_subcats'] ?? null);
        if ($show_subcats) {
            $category_params['show_subcats'] = $show_subcats;
        }

        $hide_pages = get_option('hide_pages', $this->params['id'] ?? $this->params['hide_pages'] ?? null);
        if ($hide_pages) {
            $category_params['hide_pages'] = $hide_pages;
        }

        $filter_only_in_stock = get_option('filter_only_in_stock', $this->params['id'] ?? $this->params['filter_only_in_stock'] ?? null);
        if ($filter_only_in_stock) {
            $category_params['filter_only_in_stock'] = $filter_only_in_stock;
        }

        // Get template
        $template = isset($viewData['template']) ? $viewData['template'] : 'default';
        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        // Pass category parameters to the view
        $viewData['params'] = $category_params;


        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
