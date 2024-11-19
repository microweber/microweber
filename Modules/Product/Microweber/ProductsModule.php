<?php

namespace Modules\Product\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Content\Microweber\ContentModule;
use Modules\Product\Filament\ProductsModuleSettings;
use Modules\Product\Models\Product;
use \MicroweberPackages\Option\Models\Option;

class ProductsModule extends ContentModule
{
    public static string $name = 'Products Module';
    public static string $module = 'shop/products';
    public static string $icon = 'modules.product-icon';
    public static string $categories = 'products';
    public static int $position = 30;
    public static string $settingsComponent = ProductsModuleSettings::class;
    public static string $templatesNamespace = 'modules.product::templates';



    public static function getQueryBuilderFromOptions($optionsArray = []): \Illuminate\Database\Eloquent\Builder
    {
        return Product::query()->where('is_active', 1);
    }


}
