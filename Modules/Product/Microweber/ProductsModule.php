<?php

namespace Modules\Product\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Product\Filament\ProductsModuleSettings;
use Modules\Product\Models\Product;

class ProductsModule extends BaseModule
{
    public static string $name = 'Products Module';
    public static string $module = 'product';
    public static string $icon = 'modules.product-icon';
    public static string $categories = 'products';
    public static int $position = 30;
    public static string $settingsComponent = ProductsModuleSettings::class;
    public static string $templatesNamespace = 'modules.product::templates';

    public function render()
    {
        $viewData = $this->getViewData();
        $viewData['products'] = static::getQueryBuilderFromOptions($viewData['options'])->get();
        $template = $viewData['template'] ?? 'default';

        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }

    public static function getQueryBuilderFromOptions($optionsArray = []): \Illuminate\Database\Eloquent\Builder
    {
        return Product::query()->where('is_active', 1);
    }
}
