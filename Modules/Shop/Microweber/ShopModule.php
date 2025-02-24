<?php

namespace Modules\Shop\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Shop\Filament\ShopModuleSettings;

class ShopModule extends BaseModule
{
    public static string $name = 'Shop';
    public static string $module = 'shop';
    public static string $icon = 'modules.shop-icon';
    public static string $categories = 'commerce';
    public static int $position = 10;
    public static string $settingsComponent = ShopModuleSettings::class;
    public static string $templatesNamespace = 'modules.shop::templates';


    public function render()
    {
        $viewData = $this->getViewData();

        // Build shop parameters
        $shop_params = [];

        // Get content from ID
        $content_from_id = get_option('content_from_id', $this->params['id'] ?? null);
        if ($content_from_id) {
            $shop_params['content_from_id'] = $content_from_id;
        }

        // Get default sort
        $default_sort = get_option('default_sort', $this->params['id'] ?? null);
        if ($default_sort) {
            $shop_params['default_sort'] = $default_sort;
        }

        // Get default limit
        $default_limit = get_option('default_limit', $this->params['id'] ?? null);
        if ($default_limit) {
            $shop_params['default_limit'] = $default_limit;
        }

        // Get filtering options
        $filtering_by_tags = get_option('filtering_by_tags', $this->params['id'] ?? null);
        if ($filtering_by_tags) {
            $shop_params['filtering_by_tags'] = $filtering_by_tags;
        }

        $filtering_by_categories = get_option('filtering_by_categories', $this->params['id'] ?? null);
        if ($filtering_by_categories) {
            $shop_params['filtering_by_categories'] = $filtering_by_categories;
        }

        $filtering_by_custom_fields = get_option('filtering_by_custom_fields', $this->params['id'] ?? null);
        if ($filtering_by_custom_fields) {
            $shop_params['filtering_by_custom_fields'] = $filtering_by_custom_fields;
        }

        // Get template
        $template = isset($viewData['template']) ? $viewData['template'] : 'default';
        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        // Pass shop parameters to the view
        $viewData['shop_params'] = $shop_params;
        $viewData['params'] = $this->params;


        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
