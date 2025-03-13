<?php

namespace Modules\Category\Filament\Admin\Resources;



class ShopCategoryResource extends CategoryResource
{
    protected static ?string $navigationGroup = 'Shop';

    public static function getPages(): array
    {
        return [
            'index' => \Modules\Category\Filament\Admin\Resources\ShopCategoryResource\Pages\ListShopCategories::route('/'),
            'create' => \Modules\Category\Filament\Admin\Resources\ShopCategoryResource\Pages\CreateShopCategory::route('/create'),
            'edit' => \Modules\Category\Filament\Admin\Resources\ShopCategoryResource\Pages\EditShopCategory::route('/{record}/edit'),
        ];
    }
}
