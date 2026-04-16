<?php

namespace Modules\Product\Filament;

use Modules\Content\Filament\ContentModuleSettings;
use Modules\Product\Models\Product;

class ProductsModuleSettings extends ContentModuleSettings
{

    public string $module = 'shop/products';
    public string $contentModelClass = Product::class;

}
