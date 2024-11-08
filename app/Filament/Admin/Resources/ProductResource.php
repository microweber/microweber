<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductResource\Pages;
use MicroweberPackages\Product\Models\Product;
use Modules\Content\Filament\Admin\ContentResource;

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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
