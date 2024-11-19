<?php

namespace Modules\Product\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductResource\Pages;
use Modules\Content\Filament\Admin\ContentResource;
use Modules\Product\Models\Product;

class ProductResource extends ContentResource
{

    protected static ?string $model = Product::class;

    protected static ?string $navigationGroup = 'Shop';

    protected static bool $shouldRegisterNavigation = true;

    protected static string $contentType = 'product';
    protected static string $subType = 'product';

    public static function getPages(): array
    {
        return [
            'index' => ProductResource\Pages\ListProducts::route('/'),
            'create' => ProductResource\Pages\CreateProduct::route('/create'),
            'edit' => ProductResource\Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
